package project.lighthouse.autotests.helper;

import project.lighthouse.autotests.StaticData;

public class UrlHelper {

    public static String getApiUrl() {
        return StaticData.WEB_DRIVER_BASE_URL.replace("webfront", "api");
    }

    public static String getApiUrl(String url) {
        return String.format("%s/api/1%s", getApiUrl(), url);
    }

    public static String getWebFrontUrl() {
        return StaticData.WEB_DRIVER_BASE_URL;
    }
}
