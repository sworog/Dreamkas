package ru.dreamkas.steps.api.builder;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.Product;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.api.objects.sale.SaleObject;
import ru.dreamkas.api.objects.sale.SaleProduct;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;

import java.io.IOException;

public class SaleBuilderSteps {

    private SaleObject sale;

    @Step
    public void createSale(String date) throws JSONException {
        String convertedDate = DateTimeHelper.getDate(date);
        ApiStorage.getCustomVariableStorage().getSalesMap().put(date, convertedDate);
        sale = new SaleObject(convertedDate);
    }

    @Step
    public void payWithCash(String amountTendered) throws JSONException {
        sale.setPaymentMethod("cash");
        sale.setAmountTendered(amountTendered);
    }

    @Step
    public void payWithBankCard() throws JSONException {
        sale.setPaymentMethod("bankcard");
    }

    @Step
    public void putProductToSale(String productName,
                                 String quantity,
                                 String price) throws JSONException {
        Product product = ApiStorage.getCustomVariableStorage().getProducts().get(productName);
        SaleProduct saleProduct = new SaleProduct(product.getId(), quantity, price);
        sale.putProduct(saleProduct);
    }

    @Step
    public void registerSale(String email,
                             String storeName) throws JSONException, IOException {
        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        Store store = ApiStorage.getCustomVariableStorage().getStores().get(storeName);
        sale.setStore(store);
        new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(sale);
        ApiStorage.getCustomVariableStorage().setLastSaleObject(sale);
    }
}
