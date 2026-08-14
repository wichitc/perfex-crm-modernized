<?php

namespace Perfexcrm\EInvoice\Data;

use app\services\utilities\Str;

class Item extends Data
{
    private static array $placeholders;

    public static function getPlaceholders(): array
    {
        if (! isset(self::$placeholders)) {
            $placeholders = [
                '{{LINE_ITEM_ID}}',
                '{{LINE_ITEM_ORDER}}',
                '{{LINE_ITEM_NAME}}',
                '{{LINE_ITEM_DESCRIPTION}}',
                '{{LINE_ITEM_QUANTITY_NUMBER}}',
                '{{LINE_ITEM_QUANTITY_UNIT}}',
                '{{LINE_ITEM_UNIT_PRICE}}',
                '{{LINE_ITEM_TOTAL}}',
            ];

            $placeholders = static::addCustomFieldsToPlaceholders($placeholders, 'items');

            self::$placeholders = $placeholders;
        }

        return hooks()->apply_filters('before_get_einvoice_line_items_placeholders', self::$placeholders);
    }

    public static function getTaxesPlaceholders(): array
    {
        return [
            '{{TAX_NAME}}',
            '{{TAX_RATE}}',
            '{{TAX_TOTAL}}',
        ];
    }

    /**
     * @param array{item_order: int, unit: string, rate: float, qty: int, description:string, long_description:string} $item
     */
    public function __construct(private readonly array $item, private readonly int $relId, private readonly string $relType)
    {
    }

    public function getPlaceHolderValues(): array
    {
        $values = [
            'LINE_ITEM_ID'              => static::encodeForXml($this->item['id'], false),
            'LINE_ITEM_ORDER'           => static::encodeForXml($this->item['item_order'], false),
            'LINE_ITEM_NAME'            => static::encodeForXml($this->item['description'], false),
            'LINE_ITEM_DESCRIPTION'     => static::encodeForXml(clear_textarea_breaks($this->item['long_description'])),
            'LINE_ITEM_QUANTITY_NUMBER' => static::encodeForXml($this->item['qty'], false),
            'LINE_ITEM_QUANTITY_UNIT'   => static::encodeForXml($this->item['unit']),
            'LINE_ITEM_UNIT_PRICE'      => static::encodeForXml(number_format($this->item['rate'], get_decimal_places(), null, null)),
            'LINE_ITEM_TOTAL'           => static::encodeForXml(number_format($this->item['rate'] * $this->item['qty'], get_decimal_places(), null, null)),
            'LINE_ITEM_TAXES'           => $this->getTaxes(),
        ];

        $values = static::addCustomFieldsToValues($values, 'items', $this->item['id']);

        return hooks()->apply_filters('before_get_einvoice_line_items_placeholder_values', $values, $this->item);
    }

    public function getTaxes(): array
    {
        return collect(
            match ($this->relType) {
                'invoice'     => get_invoice_item_taxes($this->item['id']),
                'credit_note' => get_credit_note_item_taxes($this->item['id']),
                default       => []
            }
        )->map(fn ($tax) => [
            'TAX_NAME'  => static::encodeForXml(Str::before($tax['taxname'], '|'), false),
            'TAX_RATE'  => static::encodeForXml($tax['taxrate']),
            'TAX_TOTAL' => static::encodeForXml(number_format((($this->item['rate'] * $this->item['qty']) / 100) * $tax['taxrate'], get_decimal_places(), null,null)),
        ])->toArray();
    }
}
