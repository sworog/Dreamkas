package ru.dreamkas.pos.controller;

import org.androidannotations.annotations.rest.Get;
import org.androidannotations.annotations.rest.Post;
import org.androidannotations.annotations.rest.RequiresHeader;
import org.androidannotations.annotations.rest.Rest;
import org.springframework.http.converter.json.MappingJackson2HttpMessageConverter;

import ru.dreamkas.pos.BuildConfig;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.NamedObject;
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

    void setHeader(String name, String value);
}