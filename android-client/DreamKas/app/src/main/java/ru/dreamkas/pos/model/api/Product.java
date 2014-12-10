package ru.dreamkas.pos.model.api;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import java.math.BigDecimal;
import javax.annotation.Nullable;

import ru.dreamkas.pos.Constants;

@JsonIgnoreProperties(ignoreUnknown=true)
public class Product extends NamedObject{
    private String sku;

    @Nullable
    private String barcode;

    @Nullable
    private BigDecimal sellingPrice;

    @Nullable
    private BigDecimal purchasePrice;

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

    @Nullable
    public BigDecimal getSellingPrice() {
        return sellingPrice == null ? null : sellingPrice.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }

    public void setSellingPrice(BigDecimal value) {
        sellingPrice = value == null ? null : value.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }

    @Nullable
    public BigDecimal getPurchasePrice() {
        return purchasePrice == null ? null : purchasePrice.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }
    public void setPurchasePrice(@Nullable BigDecimal value) {
        purchasePrice = value == null ? null : value.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }

    public void setBarcode(@Nullable String barcode) {
        this.barcode = barcode;
    }

    public void setUnits(String units) {
        this.units = units;
    }
}
