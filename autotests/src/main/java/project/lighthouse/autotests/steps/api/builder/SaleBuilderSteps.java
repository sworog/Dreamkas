package project.lighthouse.autotests.steps.api.builder;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.api.objects.sale.SaleObject;
import project.lighthouse.autotests.api.objects.sale.SaleProduct;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class SaleBuilderSteps {

    private SaleObject sale;

    @Step
    public void createSale(String date,
                           String type,
                           String amountTendered) throws JSONException {
        String convertedDate = DateTimeHelper.getDate(date);
        Storage.getCustomVariableStorage().getSalesMap().put(date, convertedDate);
        sale = new SaleObject(convertedDate, type, amountTendered);
    }

    @Step
    public void putProductToSale(String productName,
                                 String quantity,
                                 String price) throws JSONException {
        Product product = Storage.getCustomVariableStorage().getProducts().get(productName);
        SaleProduct saleProduct = new SaleProduct(product.getId(), quantity, price);
        sale.putProduct(saleProduct);
    }

    @Step
    public void registerSale(String email,
                             String storeName) throws JSONException, IOException {
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        Store store = Storage.getCustomVariableStorage().getStores().get(storeName);
        sale.setStore(store);
        new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(sale);
    }
}
