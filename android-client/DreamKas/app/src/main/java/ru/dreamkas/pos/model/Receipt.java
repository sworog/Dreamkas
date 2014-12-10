package ru.dreamkas.pos.model;

import java.math.BigDecimal;
import java.util.ArrayList;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.model.api.Product;

public class Receipt extends ArrayList<ReceiptItem> {
    BigDecimal mTotal = BigDecimal.ZERO;
    private BigDecimal mAmountTendered;

    public BigDecimal getAmountTendered() {
        return mAmountTendered;
    }

    public void setAmountTendered(BigDecimal value) {
        mAmountTendered = value;
    }

    public enum PaymentMethod { BANCCARD, CASH }

    private PaymentMethod mPaymentMethod;

    public PaymentMethod getPaymentMethod() {
        return mPaymentMethod;
    }

    public void setPaymentMethod(PaymentMethod paymentMethod) {
        this.mPaymentMethod = paymentMethod;
    }

    public BigDecimal getTotal(){
        return mTotal == null ? null : mTotal.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    }

    public void changeTo(BigDecimal delta){
        mTotal = mTotal.add(delta);
    }

    public boolean add(Product product) {
        if (product.getSellingPrice() != null){
            mTotal = mTotal.add(product.getSellingPrice());
        }
        return super.add(new ReceiptItem(product));
    }



    @Override
    public void clear() {
        mTotal = BigDecimal.ZERO;
        super.clear();
    }
}
