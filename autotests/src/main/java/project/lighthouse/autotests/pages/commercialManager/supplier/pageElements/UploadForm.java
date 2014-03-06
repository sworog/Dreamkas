package project.lighthouse.autotests.pages.commercialManager.supplier.pageElements;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

import java.io.File;

/**
 * Page upload form element for supplier create/edit page object
 */
public class UploadForm extends CommonPageObject {

    public UploadForm(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void uploadFile(File file) {
        WebElement uploadFieldWebElement = findElement(By.name("file"));
        upload(file.getPath()).to(uploadFieldWebElement);
    }

    public ButtonFacade getUploadButton() {
        return new ButtonFacade(this, "Загрузить");
    }

    public ButtonFacade getReplaceFileButton() {
        return new ButtonFacade(this, "Заменить");
    }

    public LinkFacade getDeleteFileButton() {
        return new LinkFacade(this, "Удалить");
    }

    public WebElement getUploadedFileNameLinkWebElement() {
        return findVisibleElement(By.name("agreementLink"));
    }

    public String getUploadedFileName() {
        return getUploadedFileNameLinkWebElement().getText();
    }

    public void waitForUploadComplete() {
        new PreLoader(getDriver(), 30).await();
    }
}
