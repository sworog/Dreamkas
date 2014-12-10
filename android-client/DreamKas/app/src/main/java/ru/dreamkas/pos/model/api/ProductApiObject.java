package ru.dreamkas.pos.model.api;


import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;

import javax.annotation.Nullable;

//@JsonIgnoreProperties(ignoreUnknown=true)

//@JsonInclude(JsonInclude.Include.NON_DEFAULT)
public class ProductApiObject {
    private String name;
    private String units;
    private String vat;
    private String barcode;

    //@JsonInclude(value= JsonInclude.Include.NON_NULL)
    private Double sellingPrice;
    private Double purchasePrice;
    private String type = "unit";
    private String subcategory;

    public ProductApiObject(String name, String units, String barcode, String vat, Double sellingPrice, Double purchasePrice, String subcategory) {
        this.name = name;
        this.units = units;
        this.barcode = barcode;
        this.sellingPrice = sellingPrice;
        this.purchasePrice = purchasePrice;
        this.subcategory = subcategory;
        this.vat = vat;
    }

    public ProductApiObject(String name, String units, String barcode, Double sellingPrice, String subcategory) {
        this.name = name;
        this.units = units;
        this.barcode = barcode;
        this.sellingPrice = sellingPrice;
        this.subcategory = subcategory;
    }

    public String getName(){
        return name;
    }

    public String getUnits() {
        return units;
    }

    public String getVat() {
        return vat;
    }

    public String getBarcode() {
        return barcode;
    }

    public Double getSellingPrice() {
        return sellingPrice;
    }

    public Double getPurchasePrice() {
        return purchasePrice;
    }

    public String getType() {
        return type;
    }

    public String getSubCategory() {
        return subcategory;
    }
}