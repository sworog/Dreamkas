package ru.crystals.vaverjanov.dreamkas.model.api;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class NamedObject
{
    private String id;
    private String name;

    public NamedObject(String id, String name)
    {
        this.id = id;
        this.name = name;
    }

    public NamedObject()
    {

    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}
