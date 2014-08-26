package project.lighthouse.autotests.jbehave.api.builder.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.steps.api.builder.SupplierReturnBuilderSteps;

import java.io.IOException;

public class SupplierReturnBuilderUserSteps {

    @Steps
    SupplierReturnBuilderSteps supplierReturnBuilderSteps;

    @Given("пользователь создает апи объект возвратом поставщику с датой '$date', статусом Оплачено '$paid', магазином с именем '$storeName', поставщиком с именем '$supplierName'")
    public void givenTheUserWithEmailCreatesInvoiceApiObject(String date, Boolean paid, String storeName, String supplierName) throws JSONException {
        Store store = StaticData.stores.get(storeName);
        Supplier supplier = StaticData.suppliers.get(supplierName);
        supplierReturnBuilderSteps.build(date, store.getId(), paid, supplier.getId());
    }

    @Given("пользователь добавляет продукт с именем '$name', ценой '$price' и количеством '$quantity' к апи объекту возврата поставщику")
    public void givenTheUserAddsTheProductToInvoiceApiObject(String name, String price, String quantity) throws JSONException {
        Product product = StaticData.products.get(name);
        supplierReturnBuilderSteps.addProduct(
                product.getId(),
                quantity,
                price);
    }

    @Given("пользователь с адресом электронной почты '$email' создает возврат поставщику через конструктор накладных")
    public void givenTheUserWithEmailCreatesInvoiceWithBuilderSteps(String email) throws IOException, JSONException {
        supplierReturnBuilderSteps.send(email);
    }
}
