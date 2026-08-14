<?php
defined('BASEPATH') or exit('No direct script access allowed');

require __DIR__ . '/REST_Controller.php';
require_once __DIR__ . '/../libraries/Api_Mcp_Registry.php';

/**
 * @api {post} api/batch Batch Operations
 * @apiVersion 3.0.0
 * @apiName BatchOperations
 * @apiGroup Batch
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription Execute up to 50 operations in one request, in order, with continue-on-error.
 * Operations use the same names, arguments and per-operation permission rules as the MCP tools
 * (e.g. customers_create, invoices_get, leads_search). Each item returns status plus result or error,
 * with completed/failed counters in the envelope.
 * @apiParamExample {json} Request-Example:
 * {
 *   "operations": [
 *     {"tool": "customers_create", "arguments": {"data": {"company": "Acme LTD"}}},
 *     {"tool": "invoices_get",     "arguments": {"id": 5}}
 *   ]
 * }
 */
class Batch extends REST_Controller
{
    const MAX_OPERATIONS = 50;

    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $raw     = file_get_contents('php://input');
        $decoded = json_decode($raw, true);

        $operations = null;
        if (is_array($decoded) && isset($decoded['operations'])) {
            $operations = $decoded['operations'];
        } elseif (is_array($this->post('operations'))) {
            $operations = $this->post('operations');
        }

        if (!is_array($operations) || empty($operations)) {
            $this->api_validation_error(
                ['operations' => 'Provide an operations array: [{"tool": "...", "arguments": {...}}]'],
                'Batch validation failed'
            );
        }
        if (count($operations) > self::MAX_OPERATIONS) {
            $this->api_validation_error(
                ['operations' => 'Too many operations: max ' . self::MAX_OPERATIONS . ' per request'],
                'Batch validation failed'
            );
        }

        $granted   = $this->granted_permissions();
        $results   = [];
        $completed = 0;
        $failed    = 0;

        foreach (array_values($operations) as $index => $operation) {
            $tool = is_array($operation) && isset($operation['tool']) ? (string)$operation['tool'] : '';
            $args = is_array($operation) && isset($operation['arguments']) && is_array($operation['arguments'])
                ? $operation['arguments'] : [];

            if ($tool === '') {
                $failed++;
                $results[] = ['index' => $index, 'status' => false, 'error' => 'Missing tool name'];
                continue;
            }

            try {
                $result = Api_Mcp_Registry::execute($tool, $args, $this, $granted);
                $completed++;
                $results[] = ['index' => $index, 'tool' => $tool, 'status' => true, 'result' => $result];
            } catch (Exception $e) {
                $failed++;
                $results[] = ['index' => $index, 'tool' => $tool, 'status' => false, 'error' => $e->getMessage()];
            }
        }

        $this->response([
            'status'    => $failed === 0,
            'completed' => $completed,
            'failed'    => $failed,
            'results'   => $results,
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Same grant resolution as the MCP controller:
     * NULL = full access; otherwise feature => [capabilities].
     */
    private function granted_permissions()
    {
        $token = isset($this->rest->key) ? $this->rest->key : '';
        if ($token === '') {
            return null;
        }

        $row = $this->db->where('token', $token)->get(db_prefix() . 'user_api')->row_array();
        if (!$row || (int)$row['permission_enable'] !== 1) {
            return null;
        }

        $rows = $this->db->where('api_id', $row['id'])
            ->get(db_prefix() . 'user_api_permissions')->result_array();

        $granted = [];
        foreach ($rows as $permission) {
            $granted[$permission['feature']][] = $permission['capability'];
        }
        return $granted;
    }
}
