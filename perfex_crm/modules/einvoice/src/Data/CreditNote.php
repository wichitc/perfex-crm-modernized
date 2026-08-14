<?php

namespace Perfexcrm\EInvoice\Data;

use JsonSerializable;

class CreditNote extends Data implements JsonSerializable
{
    /**
     * @var string[]
     */
    private static array $placeholders;

    public function __construct(private readonly object $creditNote)
    {
    }

    public static function getPlaceholders(): array
    {
        if (! isset(self::$placeholders)) {
            $placeholders = [
                '{{CREDIT_NOTE_ID}}',
                '{{CREDIT_NOTE_NUMBER}}',
                '{{CREDIT_NOTE_DATE}}',
                '{{CREDIT_NOTE_STATUS}}',
                '{{CREDIT_NOTE_SUBTOTAL}}',
                '{{CREDIT_NOTE_TOTAL_TAX}}',
                '{{CREDIT_NOTE_ADJUSTMENT}}',
                '{{CREDIT_NOTE_DISCOUNT_TOTAL}}',
                '{{CREDIT_NOTE_TOTAL}}',
                '{{CURRENCY_CODE}}',
                '{{CREDIT_NOTE_BILLING_ADRESS}}',
                '{{CREDIT_NOTE_BILLING_CITY}}',
                '{{CREDIT_NOTE_BILLING_STATE}}',
                '{{CREDIT_NOTE_BILLING_ZIP}}',
                '{{CREDIT_NOTE_BILLING_COUNTRY}}',
                '{{CREDIT_NOTE_SHIPPING_ADRESS}}',
                '{{CREDIT_NOTE_SHIPPING_CITY}}',
                '{{CREDIT_NOTE_SHIPPING_STATE}}',
                '{{CREDIT_NOTE_SHIPPING_ZIP}}',
                '{{CREDIT_NOTE_SHIPPING_COUNTRY}}',
            ];

            $placeholders = static::addCustomFieldsToPlaceholders($placeholders, 'credit_note');

            self::$placeholders = $placeholders;
        }

        return hooks()->apply_filters('before_get_einvoice_credit_note_placeholders', self::$placeholders);
    }

    public function getPlaceHolderValues(): array
    {
        $currency    = get_currency($this->creditNote->currency);
        $shipCountry = get_country($this->creditNote->shipping_country);
        $billCountry = get_country($this->creditNote->billing_country);
        $values      = [
            'CREDIT_NOTE_ID'                => static::encodeForXml($this->creditNote->id),
            'CREDIT_NOTE_NUMBER'            => static::encodeForXml(format_credit_note_number($this->creditNote->id)),
            'CREDIT_NOTE_DATE'              => static::encodeForXml($this->creditNote->date),
            'CREDIT_NOTE_STATUS'            => static::encodeForXml(format_credit_note_status($this->creditNote->status, '', false)),
            'CREDIT_NOTE_SUBTOTAL'          => static::encodeForXml($this->creditNote->subtotal),
            'CREDIT_NOTE_TOTAL_TAX'         => static::encodeForXml($this->creditNote->total_tax),
            'CREDIT_NOTE_ADJUSTMENT'        => static::encodeForXml($this->creditNote->adjustment),
            'CREDIT_NOTE_DISCOUNT_TOTAL'    => static::encodeForXml($this->creditNote->discount_total),
            'CREDIT_NOTE_TOTAL'             => static::encodeForXml($this->creditNote->total),
            'CURRENCY_CODE'                 => static::encodeForXml($currency->name),
            'CREDIT_NOTE_BILLING_ADRESS'    => static::encodeForXml($this->creditNote->billing_street, false),
            'CREDIT_NOTE_BILLING_CITY'      => static::encodeForXml($this->creditNote->billing_city, false),
            'CREDIT_NOTE_BILLING_STATE'     => static::encodeForXml($this->creditNote->billing_state, false),
            'CREDIT_NOTE_BILLING_ZIP'       => static::encodeForXml($this->creditNote->billing_zip, false),
            'INVOICE_BILLING_COUNTRY_NAME'  => static::encodeForXml($billCountry?->short_name, false),
            'INVOICE_BILLING_COUNTRY_ISO2'  => static::encodeForXml($billCountry?->iso2, false),
            'INVOICE_BILLING_COUNTRY_ISO3'  => static::encodeForXml($billCountry?->iso3, false),
            'CREDIT_NOTE_SHIPPING_ADRESS'   => static::encodeForXml($this->creditNote->shipping_street, false),
            'CREDIT_NOTE_SHIPPING_CITY'     => static::encodeForXml($this->creditNote->shipping_city, false),
            'CREDIT_NOTE_SHIPPING_STATE'    => static::encodeForXml($this->creditNote->shipping_state, false),
            'CREDIT_NOTE_SHIPPING_ZIP'      => static::encodeForXml($this->creditNote->shipping_zip, false),
            'INVOICE_SHIPPING_COUNTRY_NAME' => static::encodeForXml($shipCountry?->short_name, false),
            'INVOICE_SHIPPING_COUNTRY_ISO2' => static::encodeForXml($shipCountry?->iso2, false),
            'INVOICE_SHIPPING_COUNTRY_ISO3' => static::encodeForXml($shipCountry?->iso3, false),
        ];

        $values = static::addCustomFieldsToValues($values, 'credit_note', $this->creditNote->id);

        return hooks()->apply_filters('before_get_einvoice_credit_note_placeholder_values', $values, $this->creditNote);
    }

    public function items(): array
    {
        return collect($this->creditNote->items)
            ->map(fn ($item) => (new Item($item, $this->creditNote->id, 'credit_note'))->getPlaceHolderValues())
            ->toArray();
    }

    public function customer(): array
    {
        return (new Customer($this->creditNote->client))->getPlaceHolderValues();
    }

    public function jsonSerialize(): array
    {
        $data = $this->getPlaceHolderValues();

        $data = array_merge(
            $data,
            (new Company())->getPlaceHolderValues(),
            $this->customer()
        );

        $data['LINE_ITEMS'] = $this->items();

        return $data;
    }
}
