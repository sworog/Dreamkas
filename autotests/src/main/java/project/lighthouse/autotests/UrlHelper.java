package project.lighthouse.autotests;

public class UrlHelper {

    public static String getApiUrl() {
        return StaticData.WEB_DRIVER_BASE_URL.replace("webfront", "api");
    }

    public static String getWebFrontUrl() {
        return StaticData.WEB_DRIVER_BASE_URL;
    }
}
