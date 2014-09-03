package project.lighthouse.autotests.jbehave.api.builder.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.builder.StockInBuilderSteps;
import project.lighthouse.autotests.storage.Storage;

public class StockInBuilderUserSteps {

    @Steps
    StockInBuilderSteps stockInBuilderSteps;

    @Given("пользователь создает апи объект оприходования с датой '$date', магазином с именем '$storeName'")
    public void givenTheUserCreatesWriteOffApiObject(String date, String storeName) {
        Store store = Storage.getCustomVariableStorage().getStores().get(storeName);
        stockInBuilderSteps.build(store.getId(), date);
    }

    @Given("пользователь добавляет к апи объекту оприходования продукт с именем '$name', ценой '$price', количеством '$quantity'")
    public void givenTheUserAddsTheProductToWriteOffApiObject(String name, String price, String quantity) {
        Product product = Storage.getCustomVariableStorage().getProducts().get(name);
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
