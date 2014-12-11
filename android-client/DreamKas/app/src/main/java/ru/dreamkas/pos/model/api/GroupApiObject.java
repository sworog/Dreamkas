package ru.dreamkas.pos.model.api;


import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class GroupApiObject {
    private String name;

    public GroupApiObject(String name){
        this.name = name;
    }

    public String getName(){
        return name;
    }
}