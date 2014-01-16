package project.lighthouse.autotests.api;

import org.apache.http.client.config.RequestConfig;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClientBuilder;

public class HttpClientFacade {

    public CloseableHttpClient build() {
        RequestConfig requestConfig = RequestConfig
                .custom()
                .setConnectTimeout(15000)
                .setConnectionRequestTimeout(15000)
                .setSocketTimeout(15000)
                .build();
        return HttpClientBuilder
                .create()
                .setDefaultRequestConfig(requestConfig)
                .build();
    }
}
