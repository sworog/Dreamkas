package ru.dreamkas.pos.controller;

import org.androidannotations.annotations.rest.Get;
import org.androidannotations.annotations.rest.Post;
import org.androidannotations.annotations.rest.RequiresHeader;
import org.androidannotations.annotations.rest.Rest;
import org.springframework.http.converter.json.MappingJackson2HttpMessageConverter;

import ru.dreamkas.pos.BuildConfig;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.GroupApiObject;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.model.api.ProductApiObject;
import ru.dreamkas.pos.model.api.ReceiptApiObject;
import ru.dreamkas.pos.model.api.SaleApiObject;
import ru.dreamkas.pos.model.api.StoreApiObject;
import ru.dreamkas.pos.model.api.collections.NamedObjects;
import ru.dreamkas.pos.model.api.Token;
import ru.dreamkas.pos.model.api.collections.Products;

@Rest(rootUrl = BuildConfig.ServerAddress, converters = { MappingJackson2HttpMessageConverter.class })
public interface DreamkasRestClient {
    @Post("/oauth/v2/token")
    Token Auth(AuthObject authObject);

    @Get("/api/1/catalog/groups")
    @RequiresHeader("Authorization")
    NamedObjects getGroups();

    @Get("/api/1/stores")
    @RequiresHeader("Authorization")
    NamedObjects getStores();

    @Get("/api/1/stores/{store}")
    @RequiresHeader("Authorization")
    NamedObject getStore(CharSequence store);

    @Get("/api/1/products/search?properties[]=name&properties[]=sku&properties[]=barcode&query={query}")
    @RequiresHeader("Authorization")
    Products searchProducts(CharSequence query);

    @Post("/api/1/stores")
    @RequiresHeader("Authorization")
    NamedObject createStore(StoreApiObject store);

    void setHeader(String name, String value);

    @Post("/api/1/catalog/groups")
    @RequiresHeader("Authorization")
    NamedObject createGroup(GroupApiObject group);

    @Post("/api/1/products")
    @RequiresHeader("Authorization")
    Product createProduct(ProductApiObject product);

    @Post("/api/1/stores/{store}/sales")
    @RequiresHeader("Authorization")
    SaleApiObject registerReceipt(CharSequence store, ReceiptApiObject receipt);
}