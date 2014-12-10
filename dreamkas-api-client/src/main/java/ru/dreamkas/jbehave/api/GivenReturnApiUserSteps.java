package ru.dreamkas.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import ru.dreamkas.steps.api.builder.ReturnBuilderSteps;

import java.io.IOException;

public class GivenReturnApiUserSteps {

    @Steps
    ReturnBuilderSteps returnBuilderSteps;

    @Given("пользователь создает возврат с последнего чека c датой '$date'")
    public void gicenUserCreatesReturnWithCashType(String date) throws JSONException {
        returnBuilderSteps.createReturnFromLastSale(date);
    }

    @Given("пользователь добавляет товар в чек возврата с именем '$productName', количеством '$quantity'")
    public void givenUserAddsProductToReturn(String productName,
                                             String quantity) throws JSONException {
        returnBuilderSteps.putProductToReturn(productName, quantity);
    }

    @Given("пользователь с адресом электронной почты '$email' в магазине с именем '$storeName' совершает возврат по чеку")
    public void givenUserWithEmailRigisterReturnInTheStoreWithName(String email, String storeName) throws IOException, JSONException {
        returnBuilderSteps.registerReturn(email, storeName);
    }
}
