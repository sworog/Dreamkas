package ru.dreamkas.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.Product;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.api.objects.returne.ReturnObject;
import ru.dreamkas.api.objects.returne.ReturnProduct;
import ru.dreamkas.api.objects.sale.SaleObject;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;
import ru.dreamkas.apihelper.DateTimeHelper;

import java.io.IOException;

public class ReturnBuilderSteps extends ScenarioSteps {

    private ReturnObject returnObject;

    @Step
    public void createReturnFromLastSale(String date) throws JSONException {
        String convertedDate = DateTimeHelper.getDate(date);
        returnObject = new ReturnObject(convertedDate);
        SaleObject lastSaleObject = ApiStorage.getCustomVariableStorage().getLastSaleObject();
        returnObject.setSaleObject(lastSaleObject);
    }

    @Step
    public void putProductToReturn(String productName,
                                   String quantity) throws JSONException {
        Product product = ApiStorage.getCustomVariableStorage().getProducts().get(productName);
        ReturnProduct returnProduct = new ReturnProduct(product.getId(), quantity);
        returnObject.putProduct(returnProduct);
    }

    @Step
    public void registerReturn(String email,
                               String storeName) throws JSONException, IOException {
        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        Store store = ApiStorage.getCustomVariableStorage().getStores().get(storeName);
        returnObject.setStore(store);
        new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(returnObject);
    }
}
