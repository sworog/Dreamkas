package ru.dreamkas.api.http;

import java.io.IOException;

/**
 * Interface to implement simple http get/post requests
 */
public interface AnonymousHttpRequestable {

    public String executeSimplePostRequest(String targetUrl, String urlParameters) throws IOException;
}
