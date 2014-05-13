package project.lighthouse.autotests.api;

import java.io.IOException;

/**
 * Interface to implement simple http get/post requests
 */
public interface SimpleHttpRequestable {

    public String executeSimplePostRequest(String targetUrl, String urlParameters) throws IOException;
}
