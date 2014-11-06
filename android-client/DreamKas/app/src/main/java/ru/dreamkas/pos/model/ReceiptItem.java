package ru.dreamkas.pos.model;

import java.math.BigDecimal;
import ru.dreamkas.pos.model.api.Product;

public class ReceiptItem{
    private Product mProduct;
    private BigDecimal mQuantity;
    private BigDecimal mSellingPrice;

    public ReceiptItem(Product product) {
        setProduct(product);
        mQuantity = new BigDecimal(1);

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
        return mQuantity;
    }
    public void setQuantity(BigDecimal quantity) {
        mQuantity = quantity;
    }

    public BigDecimal getSellingPrice() {
        return mSellingPrice;
    }
    public void setSellingPrice(BigDecimal sellingPrice) {
        mSellingPrice = sellingPrice;
    }

    public Product getProduct() {
        return mProduct;
    }
    public void setProduct(Product product) {
        this.mProduct = product;
    }

    public BigDecimal getTotal() {
        if(getSellingPrice() != null && getQuantity() != null){
            return getSellingPrice().multiply(getQuantity());
        }
        return BigDecimal.ZERO;
    }




}
