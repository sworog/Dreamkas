package project.lighthouse.autotests.api;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpEntityEnclosingRequestBase;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpPut;
import org.apache.http.entity.StringEntity;
import org.apache.http.message.BasicHeader;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.Iterator;

import static junit.framework.Assert.fail;

public class HttpExecutor implements SimpleHttpRequestable, HttpRequestable {

    private String userName;
    private String password;

    private HttpExecutor(String userName, String password) {
        this.userName = userName;
        this.password = password;
    }

    public static SimpleHttpRequestable getSimpleHttpRequestable() {
        return new HttpExecutor(null, null);
    }

    public static HttpRequestable getHttpRequestable(String userName, String password) {
        return new HttpExecutor(userName, password);
    }

    private void setHeaders(HttpEntityEnclosingRequestBase httpEntityEnclosingRequestBase) {
        httpEntityEnclosingRequestBase.setHeader("Accept", "application/json");
        httpEntityEnclosingRequestBase.setHeader("Authorization", "Bearer " + new AccessToken(userName, password).get());
    }

    public HttpPost getHttpPost(String url) {
        HttpPost httpPost = new HttpPost(url);
        setHeaders(httpPost);
        return httpPost;
    }

    private HttpPut getHttpPut(String url) {
        HttpPut httpPut = new HttpPut(url);
        setHeaders(httpPut);
        return httpPut;
    }

    private StringEntity getStringEntity(String data) throws UnsupportedEncodingException {
        StringEntity entity = new StringEntity(data, "UTF-8");
        entity.setContentType("application/json;charset=UTF-8");
        entity.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=UTF-8"));
        return entity;
    }

    public String executeHttpMethod(HttpEntityEnclosingRequestBase httpEntityEnclosingRequestBase) throws IOException {
        HttpResponse response = new HttpClientFacade().build().execute(httpEntityEnclosingRequestBase);
        HttpEntity httpEntity = response.getEntity();
        if (httpEntity != null) {
            String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
            validateResponseMessage(httpEntityEnclosingRequestBase.getURI().toURL().toString(), response, responseMessage);
            return responseMessage;
        } else {
            return "";
        }
    }

    private String executePutRequest(String targetURL, String urlParameters) throws IOException {
        HttpPut httpPut = getHttpPut(targetURL);
        StringEntity stringEntity = getStringEntity(urlParameters);
        httpPut.setEntity(stringEntity);
        return executeHttpMethod(httpPut);
    }

    public String executePutRequest(String targetUrl, JSONObject jsonObject) throws IOException {
        return executePutRequest(targetUrl, jsonObject.toString());
    }

    public String executePostRequest(String targetURL, String urlParameters) throws IOException {
        HttpPost httpPost = getHttpPost(targetURL);
        StringEntity stringEntity = getStringEntity(urlParameters);
        httpPost.setEntity(stringEntity);
        return executeHttpMethod(httpPost);
    }

    public String executeSimplePostRequest(String targetUrl, String urlParameters) throws IOException {
        HttpPost httpPost = new HttpPost(targetUrl);
        StringEntity stringEntity = getStringEntity(urlParameters);
        httpPost.setEntity(stringEntity);
        return executeHttpMethod(httpPost);
    }

    private String executePostRequest(String targetURL, JSONObject jsonObject) throws IOException {
        return executePostRequest(targetURL, jsonObject.toString());
    }

    public void executePostRequest(AbstractObject object) throws IOException, JSONException {
        String targetUrl = UrlHelper.getApiUrl(object.getApiUrl());
        JSONObject jsonObject = object.getJsonObject();
        object.setJsonObject(
                new JSONObject(
                        executePostRequest(targetUrl, jsonObject)
                )
        );
    }

    public void executeLinkRequest(String url, String linkHeader) throws JSONException, IOException {
        String data = "_method=LINK";
        HttpPost httpPost = getHttpPost(url);
        httpPost.setHeader("Link", linkHeader);
        StringEntity entity = new StringEntity(data, "UTF-8");
        entity.setContentType("application/x-www-form-urlencoded; charset=UTF-8");
        httpPost.setEntity(entity);
        executeHttpMethod(httpPost);
    }

    private void validateResponseMessage(String url, HttpResponse httpResponse, String responseMessage) {
        // TODO refactor to switch logic (status code == 400, 500 and etc)
        int statusCode = httpResponse.getStatusLine().getStatusCode();
        if (statusCode != 201 && statusCode != 200) {
            StringBuilder builder = new StringBuilder();
            JSONObject mainJsonObject = null;
            try {
                mainJsonObject = new JSONObject(responseMessage);

                if (!mainJsonObject.isNull("errors") && mainJsonObject.isNull("children")) {
                    JSONArray jsonArray = mainJsonObject.getJSONArray("errors");
                    for (int i = 0; i < jsonArray.length(); i++) {
                        builder.append(jsonArray.get(i));
                    }
                } else if (!mainJsonObject.isNull("children")) {
                    JSONObject jsonObject = mainJsonObject.getJSONObject("children");
                    for (Iterator keys = jsonObject.keys(); keys.hasNext(); ) {
                        String key = (String) keys.next();
                        if (jsonObject.get(key) instanceof JSONObject) {
                            JSONArray jsonArray = jsonObject.getJSONObject(key).getJSONArray("errors");
                            for (int i = 0; i < jsonArray.length(); i++) {
                                String message = String.format("%s : '%s'", key, jsonArray.get(i));
                                builder.append(message);
                            }
                        }
                    }
                } else {
                    String message = String.format("message: '%s', statusCode: '%s', status: '%s', statusText: '%s', currentContent: '%s'.",
                            mainJsonObject.getString("message"),
                            mainJsonObject.getString("statusCode"),
                            mainJsonObject.getString("status"),
                            mainJsonObject.getString("statusText"),
                            mainJsonObject.getString("currentContent")
                    );
                    builder.append(message);
                }
            } catch (JSONException e) {
                fail(
                        String.format("Exception message: '%s'. Json: '%s'. Url: '%s'. Response: '%s'. Response message: '%s'", e.getMessage(),
                                mainJsonObject != null
                                        ? mainJsonObject.toString()
                                        : null,
                                url,
                                httpResponse,
                                responseMessage)
                );
            }
            fail(
                    String.format("Response json error: '%s'", builder.toString())
            );
        }
    }

    public String executeGetRequest(String targetUrl) throws IOException {
        // TODO Work around for token expiration 401 : The access token provided has expired.
        HttpGet request = new HttpGet(targetUrl);
        request.setHeader("Accept", "application/json");
        request.setHeader("Authorization", "Bearer " + new AccessToken(userName, password).get());
        HttpResponse response = new HttpClientFacade().build().execute(request);

        // TODO
        // response.getStatusLine().getStatusCode() != 204
        // if status code == 204, entity will be null - > Exception

        HttpEntity httpEntity = response.getEntity();
        String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
        validateResponseMessage(targetUrl, response, responseMessage);
        return responseMessage;
    }
}
