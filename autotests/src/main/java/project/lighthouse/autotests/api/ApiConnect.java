package project.lighthouse.autotests.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.api.http.HttpRequestable;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.SubCategory;

import java.io.IOException;

public class ApiConnect {

    private final HttpRequestable httpRequestable;

    public ApiConnect(String userName, String password) throws IOException, JSONException {
        httpRequestable = HttpExecutor.getHttpRequestable(userName, password);
    }

    public SubCategory createSubCategoryThroughPost(SubCategory subCategory) throws JSONException, IOException {
        if (!StaticData.hasSubCategory(subCategory.getName())) {
            httpRequestable.executePostRequest(subCategory);
            StaticData.subCategories.put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return StaticData.subCategories.get(subCategory.getName());
        }
    }

    public String setSet10ImportUrl(String value) throws IOException, JSONException {
        JSONObject jsonObject = new JSONObject(httpRequestable.executeGetRequest(UrlHelper.getApiUrl("/configs/by/name?query=set10-import-url")));
        String targetUrl = UrlHelper.getApiUrl("/configs/" + jsonObject.getString("id"));
        return httpRequestable.executePutRequest(targetUrl, new JSONObject()
                .put("name", "set10-import-url")
                .put("value", value));
    }
}
