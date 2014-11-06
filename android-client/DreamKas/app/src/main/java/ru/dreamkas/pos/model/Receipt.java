package ru.dreamkas.pos.model;

import java.math.BigDecimal;
import java.util.ArrayList;
import ru.dreamkas.pos.model.api.Product;

public class Receipt extends ArrayList<ReceiptItem> {

    BigDecimal mTotal = BigDecimal.ZERO;
    public BigDecimal getTotal(){
        return mTotal;
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
