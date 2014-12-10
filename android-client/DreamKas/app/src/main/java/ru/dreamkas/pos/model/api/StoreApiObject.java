package ru.dreamkas.pos.model.api;


import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class StoreApiObject {
    private String address;
    private String name;

    public StoreApiObject(String address, String name){
        this.address = address;
        this.name = name;
    }

    public String getAddress(){
        return address;
    }

    public String getName(){
        return name;
    }
}