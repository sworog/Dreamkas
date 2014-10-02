package ru.crystals.vaverjanov.dreamkas.controller.requests;

import ru.crystals.vaverjanov.dreamkas.model.api.collections.Products;

public class SearchProductsRequest extends BaseRequest<Products>
{
    private CharSequence mQuery;

    public void setQuery(CharSequence query){
        mQuery = query;
    }


    public SearchProductsRequest(){
        super(Products.class);
    }

    @Override
    public Products loadDataFromNetwork() throws Exception{
        Products products = mRestClient.searchProducts(mQuery);
        return products;
    }
}
