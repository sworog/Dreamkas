package ru.dreamkas.pos.model.api;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

import javax.annotation.Nullable;

@JsonIgnoreProperties(ignoreUnknown=true)
public class Product extends NamedObject{
    private String sku;

    @Nullable
    private String barcode;

    private Integer sellingPrice;
    private String units;


    public String getSku() {
        return sku;
    }

    public String getUnits() {
        return units;
    }

    public void setSku(String sku) {
        this.sku = sku;
    }

    @Nullable
    public String getBarcode() {
        return barcode;
    }

    public Integer getSellingPrice() {
        return sellingPrice;
    }
}
