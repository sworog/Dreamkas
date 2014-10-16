package ru.crystals.vaverjanov.dreamkas.controller;

import org.androidannotations.annotations.rest.Get;
import org.androidannotations.annotations.rest.Post;
import org.androidannotations.annotations.rest.RequiresHeader;
import org.androidannotations.annotations.rest.Rest;
import org.springframework.http.converter.json.MappingJackson2HttpMessageConverter;

import ru.crystals.vaverjanov.dreamkas.BuildConfig;
import ru.crystals.vaverjanov.dreamkas.model.api.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

@Rest(rootUrl = BuildConfig.ServerAddress, converters = { MappingJackson2HttpMessageConverter.class })
public interface ILighthouseRestClient
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
    NamedObject getStore(CharSequence store);

    void setHeader(String name, String value);
    String getHeader(String name);

    //@Get("/api/1/subcategories/{group}/products")
    //NamedObject getCatalog(CharSequence name);
}