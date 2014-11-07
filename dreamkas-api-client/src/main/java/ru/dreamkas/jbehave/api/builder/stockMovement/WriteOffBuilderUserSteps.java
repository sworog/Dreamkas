package ru.dreamkas.jbehave.api.builder.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.steps.api.builder.WriteOffBuilderSteps;
import ru.dreamkas.apiStorage.ApiStorage;

public class WriteOffBuilderUserSteps {

    @Steps
    WriteOffBuilderSteps writeOffBuilderSteps;

    @Given("пользователь создает апи объект списания с датой '$date', магазином с именем '$storeName'")
    public void givenTheUserCreatesWriteOffApiObject(String date, String storeName) {
        Store store = ApiStorage.getCustomVariableStorage().getStores().get(storeName);
        writeOffBuilderSteps.build(store.getId(), DateTimeHelper.getDate(date));
    }

    @Given("пользователь добавляет к апи объекту списания продукт с именем '$name', ценой '$price', количеством '$quantity' и причиной '$cause'")
    public void givenTheUserAddsTheProductToWriteOffApiObject(String name, String price, String quantity, String cause) {
        writeOffBuilderSteps.addProduct(
                ApiStorage.getCustomVariableStorage().getProducts().get(name).getId(),
                quantity,
                price,
                cause
        );
    }

    @Given("пользователь c электронным адресом '$email' сохраняет апи объект списания")
    public void givenTheUserSendsWriteOffApiObject(String email) {
        writeOffBuilderSteps.send(email);
    }
}
