package ru.dreamkas.pos.model;

import java.util.ArrayList;

import ru.dreamkas.pos.model.api.Product;

public class Receipt extends ArrayList<Product> {

    Integer mTotal = 0;
    public Integer getTotal(){
        return mTotal;
    }

    @Override
    public boolean add(Product product) {
        if (product.getSellingPrice() != null){
            mTotal += product.getSellingPrice();
        }
        return super.add(product);
    }

    @Override
    public void clear() {
        mTotal = 0;
        super.clear();
    }
}
