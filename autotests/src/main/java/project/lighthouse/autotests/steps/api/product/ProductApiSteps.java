package project.lighthouse.autotests.steps.api.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class ProductApiSteps extends ScenarioSteps {

    @Step
    public Product createProductByUserWithEmail(String name,
                                                String units,
                                                String barcode,
                                                String vat,
                                                String purchasePrice,
                                                String sellingPrice,
                                                String groupName, String email) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(groupName);
        Product product = new Product(
                name,
                units,
                barcode,
                vat,
                purchasePrice,
                sellingPrice,
                subCategory.getId());
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        ApiConnect userApiConnect = new ApiConnect(userContainer.getEmail(), userContainer.getPassword());
        return userApiConnect.createProductThroughPost(product, subCategory);
    }

    @Step
    public void navigateToTheProductPage(String productName) throws JSONException {
        String productPageUrl = Product.getPageUrl(productName);
        getDriver().navigate().to(productPageUrl);
    }
}
