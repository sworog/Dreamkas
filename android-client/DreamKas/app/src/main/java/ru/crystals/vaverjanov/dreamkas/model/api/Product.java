package ru.crystals.vaverjanov.dreamkas.model.api;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

import javax.annotation.Nullable;

@JsonIgnoreProperties(ignoreUnknown=true)
public class Product extends NamedObject
{
    private String sku;

    @Nullable
    private String barcode;

    public String getSku() {
        return sku;
    }

    public void setSku(String sku) {
        this.sku = sku;
    }

    @Nullable
    public String getBarcode() {
        return barcode;
    }
}
