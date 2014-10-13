package ru.dreamkas.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import ru.dreamkas.steps.api.store.StoreApiSteps;

import java.io.IOException;

public class GivenStoreUserApiSteps {

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user with email '$email' creates the store with name '$name' and address '$address'")
    @Alias("пользователь с адресом электронной почты '$email' создает магазин с именем '$name' и адресом '$address'")
    public void givenTheUserWithEmailCreatesTheStoreWithNameAndAddress(String email, String name, String address) throws IOException, JSONException {
        storeApiSteps.createStoreByUserWithEmail(name, address, email);
    }
}
