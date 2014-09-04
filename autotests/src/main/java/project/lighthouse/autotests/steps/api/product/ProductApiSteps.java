package project.lighthouse.autotests.steps.api.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.helper.UUIDGenerator;
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
        SubCategory subCategory = Storage.getCustomVariableStorage().getSubCategories().get(groupName);
        Product product = new Product(
                name,
                units,
                barcode,
                vat,
                purchasePrice,
                sellingPrice,
                subCategory.getId());

        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);

        if (!subCategory.hasProduct(product)) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(product);
            subCategory.addProduct(product);
            Storage.getCustomVariableStorage().getProducts().put(product.getName(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    @Step
    public void createProductByUserWithEmailWithRandomName(String groupName, String email) throws IOException, JSONException {
        String name = UUIDGenerator.generateWithoutHyphens();
        createProductByUserWithEmail(name, "", "", "0", "", "", groupName, email);
        Storage.getCustomVariableStorage().setName(name);
    }
}
