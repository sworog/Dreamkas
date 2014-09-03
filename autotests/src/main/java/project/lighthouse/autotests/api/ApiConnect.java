package project.lighthouse.autotests.api;

import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.api.http.HttpRequestable;
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
}
