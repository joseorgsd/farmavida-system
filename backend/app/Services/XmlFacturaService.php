<?php

namespace App\Services;

use App\Models\Venta;

class XmlFacturaService
{

    public function generar(Venta $venta)
    {

        $cliente = $venta->cliente;

        $itemsXml = '';

        foreach ($venta->detalles as $detalle) {

            $itemsXml .= "

            <cac:InvoiceLine>

                <cbc:InvoicedQuantity>
                    {$detalle->cantidad}
                </cbc:InvoicedQuantity>

                <cbc:LineExtensionAmount currencyID='PEN'>
                    {$detalle->subtotal}
                </cbc:LineExtensionAmount>

                <cac:Item>

                    <cbc:Description>
                        {$detalle->producto->nombre}
                    </cbc:Description>

                </cac:Item>

                <cac:Price>

                    <cbc:PriceAmount currencyID='PEN'>
                        {$detalle->precio}
                    </cbc:PriceAmount>

                </cac:Price>

            </cac:InvoiceLine>

            ";
        }

        $xml = "<?xml version='1.0' encoding='UTF-8'?>

<Invoice
xmlns='urn:oasis:names:specification:ubl:schema:xsd:Invoice-2'
xmlns:cac='urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'
xmlns:cbc='urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'>

<cbc:ID>{$venta->numero_venta}</cbc:ID>

<cbc:IssueDate>
{$venta->created_at->format('Y-m-d')}
</cbc:IssueDate>

<cac:AccountingSupplierParty>

<cbc:CustomerAssignedAccountID>
".config('empresa.ruc')."
</cbc:CustomerAssignedAccountID>

<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>

<cac:Party>

<cbc:RegistrationName>
".config('empresa.razon_social')."
</cbc:RegistrationName>

</cac:Party>

</cac:AccountingSupplierParty>

<cac:AccountingCustomerParty>

<cbc:CustomerAssignedAccountID>
{$cliente->dni}
</cbc:CustomerAssignedAccountID>

<cac:Party>

<cbc:RegistrationName>
{$cliente->nombre}
</cbc:RegistrationName>

</cac:Party>

</cac:AccountingCustomerParty>

{$itemsXml}

<cbc:LegalMonetaryTotal>

<cbc:PayableAmount currencyID='PEN'>
{$venta->total}
</cbc:PayableAmount>

</cbc:LegalMonetaryTotal>

</Invoice>

";

        return $xml;
    }
}