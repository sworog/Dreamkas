package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.steps.api.departmentManager.WriteOffApiSteps;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

import java.io.IOException;

public class EndWriteOffApiSteps {

    @Steps
    WriteOffApiSteps writeOffApiSteps;

    @Given("there is the write off with number '$writeOffNumber'")
    public void givenThereIsTheWriteOffWithNumber(String writeOffNumber) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber);
    }

    @Given("there is the write off with number '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    @Alias("there is the write off with sku '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    public void givenThereIsTheWriteOffWithNumberInTheStoreRuledByUser(String writeOffNumber, String storeNumber, String userName) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, storeNumber, userName);
    }

    @Given("there is the write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProduct(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        createProduct(productSku, productSku, productSku, "kg", "15");
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
    }

    @Given("the user adds the product to the write off with number '$writeOffNumber' with sku '$productSku', quantity '$quantity', price '$price, cause '$cause' in the store ruled by '$userName'")
    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause, String userName) throws IOException, JSONException {
        writeOffApiSteps.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, userName);
    }

    @Given("the user navigates to new write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber, String productSku, String productUnits, String purchasePrice, String quantity, String price, String cause)
            throws IOException, JSONException {
        createProduct(productSku, productSku, productSku, productUnits, purchasePrice);
        writeOffApiSteps.createWriteOffAndNavigateToIt(writeOffNumber, productSku, quantity, price, cause);
    }

    @Given("navigate to new write off with '$writeOffNumber' number")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffAndNavigateToIt(writeOffNumber);
    }

    @Given("the user navigates to the write off with number '$writeOffNumber'")
    public void givenNavigateToTheWriteOffWithNumber(String writeNumber) throws JSONException {
        writeOffApiSteps.navigateToWriteOffPage(writeNumber);
    }

    @Given("there is the writeOff in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheWriteOffInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffThrougPost(number, userName, examplesTable);
        WriteOffSteps.examplesTable = examplesTable;
    }

    public void createProduct(String productSku, String productName, String productBarCode, String productUnits, String purchasePrice) throws IOException, JSONException {
        if (!StaticData.products.containsKey(productSku)) {
            productApi.сreateProductThroughPost(productSku, productName, productBarCode, productUnits, purchasePrice);
        }
    }
}
