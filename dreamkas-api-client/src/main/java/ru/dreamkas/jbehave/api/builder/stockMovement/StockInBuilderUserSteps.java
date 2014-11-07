package ru.dreamkas.jbehave.api.builder.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.api.objects.Product;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.steps.api.builder.StockInBuilderSteps;
import ru.dreamkas.apiStorage.ApiStorage;

public class StockInBuilderUserSteps {

    @Steps
    StockInBuilderSteps stockInBuilderSteps;

    @Given("пользователь создает апи объект оприходования с датой '$date', магазином с именем '$storeName'")
    public void givenTheUserCreatesWriteOffApiObject(String date, String storeName) {
        Store store = ApiStorage.getCustomVariableStorage().getStores().get(storeName);
        stockInBuilderSteps.build(store.getId(), DateTimeHelper.getDate(date));
    }

    @Given("пользователь добавляет к апи объекту оприходования продукт с именем '$name', ценой '$price', количеством '$quantity'")
    public void givenTheUserAddsTheProductToWriteOffApiObject(String name, String price, String quantity) {
        Product product = ApiStorage.getCustomVariableStorage().getProducts().get(name);
        stockInBuilderSteps.addProduct(
                product.getId(),
                quantity,
                price
        );
    }

    @Given("пользователь c электронным адресом '$email' сохраняет апи объект оприходования")
    public void givenTheUserSendsWriteOffApiObject(String email) {
        stockInBuilderSteps.send(email);
    }
}
