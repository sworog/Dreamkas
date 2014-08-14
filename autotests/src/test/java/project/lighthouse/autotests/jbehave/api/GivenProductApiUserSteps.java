package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.product.ProductApiSteps;

import java.io.IOException;

public class GivenProductApiUserSteps {

    @Steps
    ProductApiSteps productApiSteps;

    @Given("the user with email '$email' creates the product with name '$name', units '$units', barcode '$barcode', vat '$vat', purchasePrice '$purchasePrice', sellingPrice '$sellingPrice' in the group with name '$groupName'")
    @Alias("пользователь с адресом электронной почты '$email' создает продукт с именем '$name', еденицами измерения '$units', штрихкодом '$barcode', НДС '$vat', ценой закупки '$purchasePrice' и ценой продажи '$sellingPrice' в группе с именем '$groupName'")
    public void givenTheUserWithEmailCreatesTheProductInTheGroup(
            String email,
            String name,
            String units,
            String barcode,
            String vat,
            String purchasePrice,
            String sellingPrice,
            String groupName) throws IOException, JSONException {
        productApiSteps.createProductByUserWithEmail(name, units, barcode, vat, purchasePrice, sellingPrice, groupName, email);
    }

    @Given("the user with email '$email' creates the product with randomly generated name in the group with name '$groupName'")
    public void givenTheUserWithEmailCreatesheProductWithRandomlyGeneratedNameInTheGroupWithName(String email, String groupName) throws IOException, JSONException {
        productApiSteps.createProductByUserWithEmailWithRandomName(groupName, email);
    }
}
