package ru.dreamkas.jbehave.api.builder.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import ru.dreamkas.api.objects.Product;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.api.objects.Supplier;
import ru.dreamkas.steps.api.builder.InvoiceBuilderSteps;
import ru.dreamkas.storage.Storage;

import java.io.IOException;

public class InvoiceBuilderUserSteps {

    @Steps
    InvoiceBuilderSteps invoiceBuilderSteps;

    @Given("the user creates invoice api object with date '$date', paid status '$paid', store with name '$storeName', supplier with name '$supplierName'")
    @Alias("пользователь создает апи объект накладной с датой '$date', статусом Оплачено '$paid', магазином с именем '$storeName', поставщиком с именем '$supplierName'")
    public void givenTheUserWithEmailCreatesInvoiceApiObject(String date, Boolean paid, String storeName, String supplierName) throws JSONException {
        Store store = Storage.getCustomVariableStorage().getStores().get(storeName);
        Supplier supplier = Storage.getCustomVariableStorage().getSuppliers().get(supplierName);
        invoiceBuilderSteps.build(date, paid, store.getId(), supplier.getId());
    }

    @Given("the user adds the product with name '$name' with price '$price' and quantity '$quantity 'to invoice api object")
    @Alias("пользователь добавляет продукт с именем '$name', ценой '$price' и количеством '$quantity' к апи объекту накладной")
    public void givenTheUserAddsTheProductToInvoiceApiObject(String name, String price, String quantity) throws JSONException {
        Product product = Storage.getCustomVariableStorage().getProducts().get(name);
        invoiceBuilderSteps.addProduct(
                product.getId(),
                quantity,
                price);
    }

    @Given("the user with email '$email 'creates invoice with builders steps")
    @Alias("пользователь с адресом электронной почты '$email' создает накладную через конструктор накладных")
    public void givenTheUserWithEmailCreatesInvoiceWithBuilderSteps(String email) throws IOException, JSONException {
        invoiceBuilderSteps.send(email);
    }
}
