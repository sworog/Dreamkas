package ru.dreamkas.pos.model;

import java.math.BigDecimal;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.model.api.Product;

public class ReceiptItem{
    private Product mProduct;
    private BigDecimal mQuantity;
    private BigDecimal mSellingPrice;

    public ReceiptItem(Product product) {
        setProduct(product);
        mQuantity = BigDecimal.ONE;

        if (product.getSellingPrice() != null) {
            mSellingPrice = product.getSellingPrice();
        }
    }

    //clone constructor
    public ReceiptItem(ReceiptItem item) {
        this(item.getProduct());
        this.setQuantity(item.getQuantity());
        this.setSellingPrice(item.getSellingPrice());
    }

    public BigDecimal getQuantity() {
        return mQuantity == null ? null : mQuantity.setScale(Constants.SCALE_QUANTITY, BigDecimal.ROUND_HALF_UP);
    }
    public void setQuantity(BigDecimal quantity) {
        mQuantity = quantity == null ? null : quantity.setScale(Constants.SCALE_QUANTITY, BigDecimal.ROUND_HALF_UP);
    }

    public BigDecimal getSellingPrice() {
        return mSellingPrice == null ? null : mSellingPrice.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }
    public void setSellingPrice(BigDecimal sellingPrice) {
        mSellingPrice = sellingPrice == null ? null : sellingPrice.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }

    public Product getProduct() {
        return mProduct;
    }
    public void setProduct(Product product) {
        this.mProduct = product;
    }

    public BigDecimal getTotal() {
        if(getSellingPrice() != null && getQuantity() != null){
            return getSellingPrice().multiply(getQuantity()).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
        }
        return BigDecimal.ZERO;
    }




}
