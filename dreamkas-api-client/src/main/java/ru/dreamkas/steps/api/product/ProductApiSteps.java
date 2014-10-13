package ru.dreamkas.steps.api.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.Product;
import ru.dreamkas.api.objects.SubCategory;
import ru.dreamkas.apihelper.UUIDGenerator;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;

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
        SubCategory subCategory = ApiStorage.getCustomVariableStorage().getSubCategories().get(groupName);
        Product product = new Product(
                name,
                units,
                barcode,
                vat,
                purchasePrice,
                sellingPrice,
                subCategory.getId());

        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);

        if (!subCategory.hasProduct(product)) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(product);
            subCategory.addProduct(product);
            ApiStorage.getCustomVariableStorage().getProducts().put(product.getName(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    @Step
    public void createProductByUserWithEmailWithRandomName(String groupName, String email) throws IOException, JSONException {
        String name = UUIDGenerator.generateWithoutHyphens();
        createProductByUserWithEmail(name, "", "", "0", "", "", groupName, email);
        ApiStorage.getCustomVariableStorage().setName(name);
    }
}
