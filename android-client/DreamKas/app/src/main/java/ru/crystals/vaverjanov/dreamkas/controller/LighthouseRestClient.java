package ru.crystals.vaverjanov.dreamkas.controller;

import android.provider.ContactsContract;

import org.androidannotations.annotations.rest.Get;
import org.androidannotations.annotations.rest.Post;
import org.androidannotations.annotations.rest.RequiresHeader;
import org.androidannotations.annotations.rest.Rest;
import org.springframework.http.converter.json.MappingJackson2HttpMessageConverter;

import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.Token;

@Rest(rootUrl = "http://av.staging.api.lighthouse.pro", converters = { MappingJackson2HttpMessageConverter.class })
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

    void setHeader(String name, String value);
    String getHeader(String name);

    //@Get("/api/1/subcategories/{group}/products")
    //NamedObject getCatalog(CharSequence name);

    //uncomment it. set url from gradle? load from sqlite? settings-file?
    //void setRootUrl(String rootUrl);
}