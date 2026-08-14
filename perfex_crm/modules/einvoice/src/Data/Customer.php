<?php

namespace Perfexcrm\EInvoice\Data;

class Customer extends Data
{
    private static array $placeholders;
    private object|null $contact = null;

    public static function getPlaceholders(): array
    {
        if (! isset(self::$placeholders)) {
            $placeholders = [
                '{{CONTACT_FIRST_NAME}}',
                '{{CONTACT_LAST_NAME}}',
                '{{CONTACT_PHONE_NUMBER}}',
                '{{CONTACT_EMAIL}}',
                '{{CUSTOMER_NAME}}',
                '{{CUSTOMER_PHONE}}',
                '{{CUSTOMER_COUNTRY_NAME}}',
                '{{CUSTOMER_COUNTRY_ISO2}}',
                '{{CUSTOMER_COUNTRY_ISO3}}',
                '{{CUSTOMER_CITY}}',
                '{{CUSTOMER_ZIP}}',
                '{{CUSTOMER_STATE}}',
                '{{CUSTOMER_ADDRESS}}',
                '{{CUSTOMER_VAT_NUMBER}}',
                '{{CUSTOMER_ID}}',
            ];

            $placeholders = self::addCustomFieldsToPlaceholders($placeholders, ['customers', 'contacts']);

            self::$placeholders = $placeholders;
        }

        return hooks()->apply_filters('before_get_einvoice_customer_placeholders', self::$placeholders);
    }

    public function __construct(private readonly object $customer)
    {
        $contactId = get_primary_contact_user_id($this->customer->userid);

        if ($contactId) {
            $ci = &get_instance();
            $ci->db->where('userid', $this->customer->userid);
            $ci->db->where('id', $contactId);
            $this->contact = $ci->db->get(db_prefix() . 'contacts')->row();
        }
    }

    public function getPlaceHolderValues(): array
    {
        $country = get_country($this->customer->country);
        $values  = [
            'CONTACT_FIRST_NAME'    => static::encodeForXml($this->contact?->firstname, false),
            'CONTACT_LAST_NAME'     => static::encodeForXml($this->contact?->lastname, false),
            'CONTACT_PHONE_NUMBER'  => static::encodeForXml($this->contact?->phonenumber),
            'CONTACT_EMAIL'         => static::encodeForXml($this->contact?->email),
            'CUSTOMER_NAME'         => static::encodeForXml($this->customer->company, false),
            'CUSTOMER_PHONE'        => static::encodeForXml($this->customer->phonenumber),
            'CUSTOMER_COUNTRY_ISO2' => static::encodeForXml($country?->iso2, false),
            'CUSTOMER_COUNTRY_ISO3' => static::encodeForXml($country?->iso3, false),
            'CUSTOMER_COUNTRY_NAME' => static::encodeForXml($country?->short_name, false),
            'CUSTOMER_CITY'         => static::encodeForXml($this->customer->city, false),
            'CUSTOMER_ZIP'          => static::encodeForXml($this->customer->zip),
            'CUSTOMER_STATE'        => static::encodeForXml($this->customer->state, false),
            'CUSTOMER_ADDRESS'      => static::encodeForXml($this->customer->address, false),
            'CUSTOMER_VAT_NUMBER'   => static::encodeForXml($this->customer->vat),
            'CUSTOMER_ID'           => $this->customer->userid,
        ];

        $values = self::addCustomFieldsToValues($values, 'customers', $this->customer->userid);

        if (isset($this->contact)) {
            $values = self::addCustomFieldsToValues($values, 'contacts', $this->contact?->id);
        }

        return hooks()->apply_filters('before_get_einvoice_customer_placeholder_values', $values, $this->customer);
    }
}
