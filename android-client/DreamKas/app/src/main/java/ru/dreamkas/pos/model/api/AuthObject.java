package ru.dreamkas.pos.model.api;


import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class AuthObject{
    private String client_id;
    private String client_secret;
    private String grant_type = "password";
    private String password;
    private String username;

    public AuthObject(String client_id, String username, String password, String client_secret){
        this.client_id = client_id;
        this.client_secret = client_secret;
        this.password = password;
        this.username = username;
    }

    public String getClient_id(){
        return client_id;
    }

    public String getClient_secret(){
        return client_secret;
    }

    public String getGrant_type(){
        return grant_type;
    }

    public String getPassword(){
        return password;
    }

    public String getUsername(){
        return username;
    }
}