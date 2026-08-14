<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Barcode Library
 * Handles barcode and QR code generation for assets
 */
class Assets_barcode
{
    protected $CI;
    protected $upload_path;
    
    // Barcode types supported
    const TYPE_CODE128 = 'C128';
    const TYPE_CODE39 = 'C39';
    const TYPE_EAN13 = 'EAN13';
    const TYPE_UPCA = 'UPCA';
    const TYPE_QR = 'QRCODE';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->upload_path = ASSETS_UPLOAD_FOLDER . '/barcodes/';
        
        // Ensure barcode directory exists
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
        }
    }

    /**
     * Generate barcode for an asset
     */
    public function generate_barcode($asset_id, $code = null, $type = self::TYPE_CODE128)
    {
        $asset = $this->get_asset($asset_id);
        if (!$asset) {
            return false;
        }

        $code = $code ?: $asset->assets_code;
        $filename = 'barcode_' . $asset_id . '_' . time() . '.png';
        $filepath = $this->upload_path . $filename;

        // Generate barcode using PHP
        $barcode = $this->create_barcode_image($code, $type);
        
        if ($barcode && imagepng($barcode, $filepath)) {
            imagedestroy($barcode);
            
            // Update asset with barcode filename
            $this->CI->db->where('id', $asset_id);
            $this->CI->db->update(db_prefix().'assets', ['barcode' => $filename]);
            
            return $filename;
        }

        return false;
    }

    /**
     * Generate QR code for an asset
     */
    public function generate_qr_code($asset_id, $data = null)
    {
        $asset = $this->get_asset($asset_id);
        if (!$asset) {
            return false;
        }

        // Default QR data includes asset info and URL
        if (!$data) {
            $data = json_encode([
                'id' => $asset_id,
                'code' => $asset->assets_code,
                'name' => $asset->assets_name,
                'url' => admin_url('assets/manage_assets#' . $asset_id)
            ]);
        }

        $filename = 'qr_' . $asset_id . '_' . time() . '.png';
        $filepath = $this->upload_path . $filename;

        // Generate QR code
        $qr = $this->create_qr_image($data);
        
        if ($qr && imagepng($qr, $filepath)) {
            imagedestroy($qr);
            
            // Update asset with QR code filename
            $this->CI->db->where('id', $asset_id);
            $this->CI->db->update(db_prefix().'assets', ['qr_code' => $filename]);
            
            return $filename;
        }

        return false;
    }

    /**
     * Get barcode image URL
     */
    public function get_barcode_url($asset_id)
    {
        $asset = $this->get_asset($asset_id);
        if (!$asset || empty($asset->barcode)) {
            return null;
        }
        return base_url('modules/assets/uploads/barcodes/' . $asset->barcode);
    }

    /**
     * Get QR code image URL
     */
    public function get_qr_url($asset_id)
    {
        $asset = $this->get_asset($asset_id);
        if (!$asset || empty($asset->qr_code)) {
            return null;
        }
        return base_url('modules/assets/uploads/barcodes/' . $asset->qr_code);
    }

    /**
     * Generate barcode for all assets without one
     */
    public function generate_all_barcodes()
    {
        $this->CI->db->where('barcode IS NULL OR barcode = ""');
        $assets = $this->CI->db->get(db_prefix().'assets')->result();

        $generated = 0;
        foreach ($assets as $asset) {
            if ($this->generate_barcode($asset->id)) {
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Generate QR codes for all assets without one
     */
    public function generate_all_qr_codes()
    {
        $this->CI->db->where('qr_code IS NULL OR qr_code = ""');
        $assets = $this->CI->db->get(db_prefix().'assets')->result();

        $generated = 0;
        foreach ($assets as $asset) {
            if ($this->generate_qr_code($asset->id)) {
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Delete barcode files for an asset
     */
    public function delete_barcodes($asset_id)
    {
        $asset = $this->get_asset($asset_id);
        if (!$asset) {
            return false;
        }

        if (!empty($asset->barcode) && file_exists($this->upload_path . $asset->barcode)) {
            unlink($this->upload_path . $asset->barcode);
        }

        if (!empty($asset->qr_code) && file_exists($this->upload_path . $asset->qr_code)) {
            unlink($this->upload_path . $asset->qr_code);
        }

        return true;
    }

    /**
     * Create barcode image using pure PHP
     */
    protected function create_barcode_image($code, $type = self::TYPE_CODE128)
    {
        // Code128 encoding
        $barcode_data = $this->encode_code128($code);
        
        if (!$barcode_data) {
            return false;
        }

        $bar_width = 2;
        $height = 60;
        $padding = 10;
        $font_size = 3;
        
        $width = (strlen($barcode_data) * $bar_width) + ($padding * 2);
        $total_height = $height + 20 + $padding;
        
        $image = imagecreate($width, $total_height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        $x = $padding;
        for ($i = 0; $i < strlen($barcode_data); $i++) {
            if ($barcode_data[$i] == '1') {
                imagefilledrectangle($image, $x, $padding, $x + $bar_width - 1, $padding + $height, $black);
            }
            $x += $bar_width;
        }
        
        // Add text below barcode
        $text_x = ($width - (strlen($code) * imagefontwidth($font_size))) / 2;
        imagestring($image, $font_size, $text_x, $height + $padding + 5, $code, $black);
        
        return $image;
    }

    /**
     * Encode string to Code128 barcode pattern
     */
    protected function encode_code128($code)
    {
        $code128 = [
            ' ' => '11011001100', '!' => '11001101100', '"' => '11001100110', '#' => '10010011000',
            '$' => '10010001100', '%' => '10001001100', '&' => '10011001000', "'" => '10011000100',
            '(' => '10001100100', ')' => '11001001000', '*' => '11001000100', '+' => '11000100100',
            ',' => '10110011100', '-' => '10011011100', '.' => '10011001110', '/' => '10111001100',
            '0' => '10011101100', '1' => '10011100110', '2' => '11001110010', '3' => '11001011100',
            '4' => '11001001110', '5' => '11011100100', '6' => '11001110100', '7' => '11101101110',
            '8' => '11101001100', '9' => '11100101100', ':' => '11100100110', ';' => '11101100100',
            '<' => '11100110100', '=' => '11100110010', '>' => '11011011000', '?' => '11011000110',
            '@' => '11000110110', 'A' => '10100011000', 'B' => '10001011000', 'C' => '10001000110',
            'D' => '10110001000', 'E' => '10001101000', 'F' => '10001100010', 'G' => '11010001000',
            'H' => '11000101000', 'I' => '11000100010', 'J' => '10110111000', 'K' => '10110001110',
            'L' => '10001101110', 'M' => '10111011000', 'N' => '10111000110', 'O' => '10001110110',
            'P' => '11101110110', 'Q' => '11010001110', 'R' => '11000101110', 'S' => '11011101000',
            'T' => '11011100010', 'U' => '11011101110', 'V' => '11101011000', 'W' => '11101000110',
            'X' => '11100010110', 'Y' => '11101101000', 'Z' => '11101100010', '[' => '11100011010',
            '\\' => '11101111010', ']' => '11001000010', '^' => '11110001010', '_' => '10100110000',
            '`' => '10100001100', 'a' => '10010110000', 'b' => '10010000110', 'c' => '10000101100',
            'd' => '10000100110', 'e' => '10110010000', 'f' => '10110000100', 'g' => '10011010000',
            'h' => '10011000010', 'i' => '10000110100', 'j' => '10000110010', 'k' => '11000010010',
            'l' => '11001010000', 'm' => '11110111010', 'n' => '11000010100', 'o' => '10001111010',
            'p' => '10100111100', 'q' => '10010111100', 'r' => '10010011110', 's' => '10111100100',
            't' => '10011110100', 'u' => '10011110010', 'v' => '11110100100', 'w' => '11110010100',
            'x' => '11110010010', 'y' => '11011011110', 'z' => '11011110110', '{' => '11110110110',
            '|' => '10101111000', '}' => '10100011110', '~' => '10001011110'
        ];

        // Start Code B
        $result = '11010010000';
        $checksum = 104;

        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            if (isset($code128[$char])) {
                $result .= $code128[$char];
                $checksum += (ord($char) - 32) * ($i + 1);
            }
        }

        // Checksum
        $checksum = $checksum % 103;
        $checksum_chars = array_keys($code128);
        if ($checksum < count($checksum_chars)) {
            $result .= $code128[$checksum_chars[$checksum]] ?? '10100011000';
        }

        // Stop pattern
        $result .= '1100011101011';

        return $result;
    }

    /**
     * Create QR code image using pure PHP
     */
    protected function create_qr_image($data)
    {
        // Simple QR code generation using a basic algorithm
        // For production, consider using a library like phpqrcode
        
        $size = 200;
        $margin = 10;
        $module_size = 4;
        
        // Create a simple visual representation
        // In production, use a proper QR library
        $image = imagecreate($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        // Create a hash-based pattern (simplified QR representation)
        $hash = md5($data);
        $grid_size = 21; // Version 1 QR code
        $cell_size = (int)(($size - 2 * $margin) / $grid_size);
        
        // Draw finder patterns (corners)
        $this->draw_finder_pattern($image, $margin, $margin, $cell_size, $black, $white);
        $this->draw_finder_pattern($image, $margin, (int)($size - $margin - 7 * $cell_size), $cell_size, $black, $white);
        $this->draw_finder_pattern($image, (int)($size - $margin - 7 * $cell_size), $margin, $cell_size, $black, $white);
        
        // Fill data area based on hash
        $hash_bits = '';
        for ($i = 0; $i < strlen($hash); $i++) {
            $hash_bits .= str_pad(base_convert($hash[$i], 16, 2), 4, '0', STR_PAD_LEFT);
        }
        
        $bit_index = 0;
        for ($row = 0; $row < $grid_size; $row++) {
            for ($col = 0; $col < $grid_size; $col++) {
                // Skip finder pattern areas
                if (($row < 8 && $col < 8) || ($row < 8 && $col >= $grid_size - 8) || ($row >= $grid_size - 8 && $col < 8)) {
                    continue;
                }
                
                if ($bit_index < strlen($hash_bits) && $hash_bits[$bit_index] == '1') {
                    $x = (int)($margin + $col * $cell_size);
                    $y = (int)($margin + $row * $cell_size);
                    imagefilledrectangle($image, $x, $y, (int)($x + $cell_size - 1), (int)($y + $cell_size - 1), $black);
                }
                $bit_index = ($bit_index + 1) % strlen($hash_bits);
            }
        }
        
        return $image;
    }

    /**
     * Draw QR finder pattern
     */
    protected function draw_finder_pattern($image, $x, $y, $cell_size, $black, $white)
    {
        // Cast all coordinates to int for PHP 8 compatibility
        $x = (int)$x;
        $y = (int)$y;
        $cell_size = (int)$cell_size;
        
        // Outer black square
        imagefilledrectangle($image, $x, $y, $x + 7 * $cell_size - 1, $y + 7 * $cell_size - 1, $black);
        // Inner white square
        imagefilledrectangle($image, $x + $cell_size, $y + $cell_size, $x + 6 * $cell_size - 1, $y + 6 * $cell_size - 1, $white);
        // Center black square
        imagefilledrectangle($image, $x + 2 * $cell_size, $y + 2 * $cell_size, $x + 5 * $cell_size - 1, $y + 5 * $cell_size - 1, $black);
    }

    /**
     * Get asset helper
     */
    protected function get_asset($asset_id)
    {
        $this->CI->db->where('id', $asset_id);
        return $this->CI->db->get(db_prefix().'assets')->row();
    }

    /**
     * Parse barcode from uploaded image (placeholder for scanner integration)
     */
    public function parse_barcode_image($image_path)
    {
        // This would require a barcode reading library
        // For now, return false as placeholder
        return false;
    }

    /**
     * Search asset by barcode
     */
    public function find_by_barcode($code)
    {
        $this->CI->db->where('assets_code', $code);
        $this->CI->db->or_where('barcode', $code);
        return $this->CI->db->get(db_prefix().'assets')->row();
    }

    /**
     * Generate printable barcode labels
     */
    public function generate_label_pdf($asset_ids, $label_size = 'small')
    {
        if (!is_array($asset_ids)) {
            $asset_ids = [$asset_ids];
        }

        $this->CI->load->library('pdf');
        
        $labels = [];
        foreach ($asset_ids as $asset_id) {
            $asset = $this->get_asset($asset_id);
            if ($asset) {
                // Ensure barcode exists
                if (empty($asset->barcode)) {
                    $this->generate_barcode($asset_id);
                    $asset = $this->get_asset($asset_id);
                }
                
                $labels[] = [
                    'asset' => $asset,
                    'barcode_url' => $this->get_barcode_url($asset_id),
                    'qr_url' => $this->get_qr_url($asset_id)
                ];
            }
        }

        return $labels;
    }
}
