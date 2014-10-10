package ru.dreamkas.helper;

import ru.dreamkas.storage.Storage;

/**
 * The helper is used for getting required url for api mechanism
 */
public class UrlHelper {

    public static String getApiUrl() {
        return getWebFrontUrl().replace("webfront", "api");
    }

    public static String getApiUrl(String url) {
        return String.format("%s/api/1%s", getApiUrl(), url);
    }

    public static String getWebFrontUrl() {
        return Storage.getConfigurationVariableStorage().getProperty("webdriver.base.url");
    }
}
