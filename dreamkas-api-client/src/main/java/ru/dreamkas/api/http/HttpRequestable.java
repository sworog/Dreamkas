package ru.dreamkas.api.http;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.objects.abstraction.AbstractObject;

import java.io.IOException;

/**
 * Interface to implement http post request
 */
public interface HttpRequestable {

    public void executePostRequest(AbstractObject object) throws IOException, JSONException;

    public String executeGetRequest(String targetUrl) throws IOException;

    public String executePostRequest(String targetURL, String urlParameters) throws IOException;

    public String executePutRequest(String targetUrl, JSONObject jsonObject) throws IOException;

    public void executeLinkRequest(String url, String linkHeader) throws JSONException, IOException;
}
