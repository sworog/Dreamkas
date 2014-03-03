package project.lighthouse.autotests.pageElements.supplier;

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

    public void uploadButtonClick() {
        new ButtonFacade(this, "Загрузить").click();
    }

    public void replaceFileButtonClick() {
        new ButtonFacade(this, "Заменить").click();
    }

    public void deleteFileButtonClick() {
        new LinkFacade(this, "Удалить").click();
    }

    public String getUploadedFileName() {
        return findVisibleElement(By.name("")).getText();
    }

    public void waitForUploadComplete() {
        new PreLoader(getDriver()).await();
    }
}
