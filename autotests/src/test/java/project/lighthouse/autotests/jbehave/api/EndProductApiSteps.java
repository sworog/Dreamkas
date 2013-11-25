package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Category;
import project.lighthouse.autotests.objects.api.Group;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;

import java.io.IOException;
import java.util.Map;

public class EndProductApiSteps {

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    public Product createProduct(String name, String units, String vat, String purchasePrice, String barcode,
                                 String sku, String vendorCountry, String vendor, String info, String subCategoryName,
                                 String retailMarkupMax, String retailMarkupMin, String rounding) throws IOException, JSONException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(name, units, vat, purchasePrice, barcode, sku, vendorCountry, vendor, info, subCategory.getId(), retailMarkupMax, retailMarkupMin, rounding);
        return productApiSteps.createProductThroughPost(product, subCategory);
    }

    @Given("there is the product in subCategory with name '$subCategoryName' with data $exampleTable")
    public void givenThereIsTheProductWithData(String subCategoryName, ExamplesTable examplesTable) throws IOException, JSONException {
        String name = "", units = "", vat = "", purchasePrice = "", barcode = "",
                sku = "", vendorCountry = "", vendor = "", info = "", retailMarkupMax = "",
                retailMarkupMin = "", rounding = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "name":
                    name = elementValue;
                    break;
                case "units":
                    units = elementValue;
                    break;
                case "vat":
                    vat = elementValue;
                    break;
                case "purchasePrice":
                    purchasePrice = elementValue;
                    break;
                case "barcode":
                    barcode = elementValue;
                    break;
                case "sku":
                    sku = elementValue;
                    break;
                case "vendorCountry":
                    vendorCountry = elementValue;
                    break;
                case "vendor":
                    vendor = elementValue;
                    break;
                case "info":
                    info = elementValue;
                    break;
                case "retailMarkupMax":
                    retailMarkupMax = elementValue;
                    break;
                case "retailMarkupMin":
                    retailMarkupMin = elementValue;
                    break;
                case "rounding":
                    rounding = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
        createProduct(name, units, vat, purchasePrice, barcode, sku, vendorCountry, vendor, info, subCategoryName, retailMarkupMax, retailMarkupMin, rounding);
    }

    public Product сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        if (!StaticData.hasSubCategory(SubCategory.DEFAULT_NAME)) {
            catalogApiSteps.createDefaultSubCategoryThroughPost();
        }
        return productApiSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, SubCategory.DEFAULT_NAME);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode) throws JSONException, IOException {
        сreateProductThroughPost(name, sku, barcode, "kg", "123");
    }

    @Given("there is created product with sku '$sku'")
    public void givenThereIsCreatedProductWithSkuValue(String sku) throws JSONException, IOException {
        givenTheUserCreatesProductWithParams(sku, sku, sku, "kg");
    }

    @Given("there is created product with sku '$sku' and '$purchasePrice' purchasePrice")
    public void givenThereIsCreatedProductWithSkuValue(String sku, String purchasePrice) throws JSONException, IOException {
        givenTheUserCreatesProductWithParamsPrice(sku, sku, sku, "kg", purchasePrice);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode, String units) throws JSONException, IOException {
        сreateProductThroughPost(name, sku, barcode, units, "123");
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice")
    public void givenTheUserCreatesProductWithParamsPrice(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice, String subCategoryName) throws JSONException, IOException {
        catalogApiSteps.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, subCategoryName);
        productApiSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, subCategoryName);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName);
    }

    @Given("there is the product with '$productName' name, '$productSku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    @Alias("there is the product with <productName>, <productSku>, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    public void createProductThroughPost(String productName, String productSku, String barcode, String units, String purchasePrice,
                                         String rounding, String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(productName, productSku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, rounding);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding, retailMarkUpMax '$retailMarkupMax' and retailMarkUpmin '$retailMarkupMin'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String rounding, String groupName, String categoryName, String subCategoryName, String retailMarkupMax, String retailMarkupMin) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, retailMarkupMax, retailMarkupMin, rounding);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice, '$rounding' rounding in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String rounding, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, subCategoryName);
        productApiSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, Group.DEFAULT_NAME, Category.DEFAULT_NAME, subCategoryName, rounding);
    }

    @Given("there is the product with <productSku> and <rounding> in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String units, String vat, String purchasePrice, String barcode, String sku, String vendorCountry, String vendor, String info, String categoryName, String rounding, String productSku, String subCategoryName) throws IOException, JSONException {
        createProductThroughPost(productSku, productSku, productSku, "kg", "1", rounding, subCategoryName);
    }

    @Given("the user navigates to the product with sku '$productSku'")
    @Alias("the user navigates to the product with <productSku>")
    public void givenTheUserNavigatesToTheProduct(String productSku) throws JSONException {
        productApiSteps.navigateToTheProductPage(productSku);
    }

    @Given("the user navigates to the product with <sku>")
    public void givenTheUserNavigatesToTheProdcutWithSku(String sku) throws JSONException, IOException {
        givenThereIsCreatedProductWithSkuValue(sku, "0,01");
        givenTheUserNavigatesToTheProduct(sku);
    }
}
