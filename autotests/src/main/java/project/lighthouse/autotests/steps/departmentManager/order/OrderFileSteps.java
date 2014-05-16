package project.lighthouse.autotests.steps.departmentManager.order;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.apache.poi.ss.usermodel.Sheet;
import org.json.JSONException;
import org.json.JSONObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.api.HttpExecutor;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.WorkBookHandler;
import project.lighthouse.autotests.helper.file.FileDownloader;
import project.lighthouse.autotests.pages.departmentManager.order.OrderPage;
import project.lighthouse.autotests.storage.Storage;

import java.io.File;

import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.equalTo;
import static org.junit.Assert.assertThat;

public class OrderFileSteps extends ScenarioSteps {

    OrderPage orderPage;

    private Sheet sheet;

    @Step
    public void assertOrderDownloadedFileData(String userName, String password) throws Exception {
        By findBy = orderPage.getDownloadFileLink().getFindBy();
        String downloadLocation = orderPage.findVisibleElement(findBy).getAttribute("href");
        String response = HttpExecutor.getHttpRequestable(userName, password).executeGetRequest(downloadLocation);
        JSONObject jsonObject = new JSONObject(response);

        String fileName = jsonObject.getString("name");
        assertFileName(fileName);

        File downloadedFile = new FileDownloader(jsonObject.getString("url"), fileName).getFile();
        sheet = new WorkBookHandler(downloadedFile).getSheet();

        assertFileHeader();
        assertStoreData();
        assertFileSupplierHeader();
        assertFileColumnHeaders();
        assertSkuValue();
        assertBarCodeValue();
        assertNameValue();
        assertQuantityValue();
    }

    @Step
    public void assertFileName(String actualFileName) {
        String expectedFileName = String.format("order%s.xlsx", Storage.getOrderVariableStorage().getNumber());
        assertThat(
                actualFileName,
                equalTo(expectedFileName));
    }

    @Step
    public void assertFileHeader() {
        String expectedHeader = String.format(
                "Заказ №%s от %s",
                Storage.getOrderVariableStorage().getNumber(),
                new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"));
        assertThat(
                sheet.getRow(0).getCell(0).toString(),
                equalTo(expectedHeader));
    }

    @Step
    public void assertStoreData() throws JSONException {
        String expectedStoreData = String.format(
                "Магазин №%s. %s. %s",
                Storage.getStoreVariableStorage().getStore().getNumber(),
                Storage.getStoreVariableStorage().getStore().getAddress(),
                Storage.getStoreVariableStorage().getStore().getContacts());
        assertThat(
                sheet.getRow(1).getCell(0).toString(),
                equalTo(expectedStoreData));
    }

    @Step
    public void assertFileSupplierHeader() throws JSONException {
        String expected = String.format(
                "Поставщик \"%s\"", Storage.getOrderVariableStorage().getSupplier().getName());
        assertThat(
                sheet.getRow(2).getCell(0).toString(),
                equalTo(expected));
    }

    @Step
    public void assertFileColumnHeaders() {
        assertThat(
                sheet.getRow(4).getCell(0).toString(),
                equalTo("Код")
        );
        assertThat(
                sheet.getRow(4).getCell(1).toString(),
                equalTo("Штрихкод")
        );
        assertThat(
                sheet.getRow(4).getCell(2).toString(),
                equalTo("Наименование")
        );
        assertThat(
                sheet.getRow(4).getCell(3).toString(),
                equalTo("Кол-во")
        );
    }

    @Step
    public void assertSkuValue() throws JSONException {
        Double aDouble = sheet.getRow(5).getCell(0).getNumericCellValue();
        Integer integer = aDouble.intValue();
        assertThat(
                integer.toString(),
                equalTo(Storage.getOrderVariableStorage().getProduct().getSku())
        );
    }

    @Step
    public void assertBarCodeValue() throws JSONException {
        assertThat(
                sheet.getRow(5).getCell(1).toString(),
                equalTo(Storage.getOrderVariableStorage().getProduct().getBarCode())
        );
    }

    @Step
    public void assertNameValue() throws JSONException {
        assertThat(
                sheet.getRow(5).getCell(2).toString(),
                equalTo(Storage.getOrderVariableStorage().getProduct().getName())
        );
    }

    @Step
    public void assertQuantityValue() {
        assertThat(
                sheet.getRow(5).getCell(3).toString(),
                containsString(Storage.getOrderVariableStorage().getQuantity())
        );
    }
}
