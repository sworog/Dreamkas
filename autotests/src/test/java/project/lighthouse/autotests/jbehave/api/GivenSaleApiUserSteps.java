package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.builder.SaleBuilderSteps;

import java.io.IOException;

public class GivenSaleApiUserSteps {

    @Steps
    SaleBuilderSteps saleBuilderSteps;

    @Given("пользователь создает чек с наличным типом оплаты, внесенной суммой '$amountTendered' и датой '$date'")
    public void givenUserCreatesSaleWithCashType(String amountTendered, String date) throws JSONException {
        saleBuilderSteps.createSale(date, "cash", amountTendered);
    }

    @Given("пользователь добавляет пробивает товар в чек с именем '$productName', количеством '$quantity' и по цене '$price'")
    public void givenUserAddsProductToSale(String productName,
                                           String quantity,
                                           String price) throws JSONException {
        saleBuilderSteps.putProductToSale(productName, quantity, price);
    }

    @Given("пользователь с адресом электронной почты '$email' в магазине с именем '$storeName' совершает продажу по созданному чеку")
    public void givenUserWithEmailRigisterSaleInTheStoreWithName(String email, String storeName) throws IOException, JSONException {
        saleBuilderSteps.registerSale(email, storeName);
    }
}
