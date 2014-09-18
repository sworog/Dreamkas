package ru.crystals.vaverjanov.dreamkas.model;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown=true)
public class Token
{
    private String access_token;
    private String refresh_token;

    public String getAccess_token() {
        return access_token;
    }
    public String getRefresh_token() {
        return access_token;
    }

    public void setAccess_token(String access_token) {
        this.access_token = access_token;
    }
}
