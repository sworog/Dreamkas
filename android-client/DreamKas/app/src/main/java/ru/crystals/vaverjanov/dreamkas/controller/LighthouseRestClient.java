package ru.crystals.vaverjanov.dreamkas.controller;

import org.androidannotations.annotations.rest.Get;
import org.androidannotations.annotations.rest.Post;
import org.androidannotations.annotations.rest.RequiresHeader;
import org.androidannotations.annotations.rest.Rest;
import org.springframework.http.converter.json.MappingJackson2HttpMessageConverter;

import ru.crystals.vaverjanov.dreamkas.BuildConfig;
import ru.crystals.vaverjanov.dreamkas.model.api.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.Products;

@Rest(rootUrl = BuildConfig.ServerAddress, converters = { MappingJackson2HttpMessageConverter.class })
public interface LighthouseRestClient
{
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

    //@Get("/api/1/subcategories/{group}/products")
    //NamedObject getCatalog(CharSequence name);
}