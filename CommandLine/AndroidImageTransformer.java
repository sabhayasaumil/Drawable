import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.awt.Graphics2D;
import java.io.File;
import java.util.HashSet;
import java.util.Scanner;
import java.util.Set;

/**
 * Class to generate images for each display density andoird device resolutios.
 *
 * @author Saumil
 */
public class AndroidImageTransformer {

    // base directory path containg resource direcories.
    private final String path;

    // selected option
    private final int selection;

    // Andoird image resources names
    public final static String XXXHDPI = "XXXHDPI";
    public final static String XXHDPI = "XXHDPI";
    public final static String XHDPI = "XHDPI";
    public final static String HDPI = "HDPI";
    public final static String MDPI = "MDPI";
    public final static String LDPI = "LDPI";

    // Android image resource naming convention
    public final static String XXXHDPI_BASE_PATH = "/drawable-xxxhdpi";
    public final static String XXHDPI_BASE_PATH = "/drawable-xxhdpi";
    public final static String XHDPI_BASE_PATH = "/drawable-xhdpi";
    public final static String HDPI_BASE_PATH = "/drawable-hdpi";
    public final static String MDPI_BASE_PATH = "/drawable-mdpi";
    public final static String LDPI_BASE_PATH = "/drawable-ldpi";

    // image dimanesion ratio compared to XXXHDP 1:1
    public final static int R_XXXHDPI = 1;
    public final static int R_XXXHDPI_D = 1;

    // image dimension ratio compared to XXXHDP 3:4
    public final static int R_XXHDPI = 3;
    public final static int R_XXHDPI_D = 4;

    // image dimension ratio compared to XXXHDP 1:2
    public final static int R_XHDPI = 1;
    public final static int R_XHDPI_D = 2;

    // image dimension ratio compared to XXXHDP 3:8
    public final static int R_HDPI = 3;
    public final static int R_HDPI_D = 8;

    // image dimension ratio compared to XXXHDP 1:4
    public final static int R_MDPI = 1;
    public final static int R_MDPI_D = 4;

    // image dimension ratio compared to XXXHDP 3:16
    public final static int R_LDPI = 3;
    public final static int R_LDPI_D = 16;

    // This variables are used to store ratio of input image type to XXXHDPI_BASE_PATH. this is used to optimize logic of getting correct resolution of the resized image
    private int fin_N, fin_D;
    private final File folder_XXXHDPI, folder_XXHDPI, folder_XHDPI, folder_HDPI, folder_MDPI, folder_LDPI;

    public AndroidImageTransformer(String path, int selection) {
        this.path = path;
        this.selection = selection;
        // Directory path for images based on desity
        folder_XXXHDPI = new File(path + XXXHDPI_BASE_PATH);
        folder_XXHDPI = new File(path + XXHDPI_BASE_PATH);
        folder_XHDPI = new File(path + XHDPI_BASE_PATH);
        folder_HDPI = new File(path + HDPI_BASE_PATH);
        folder_MDPI = new File(path + MDPI_BASE_PATH);
        folder_LDPI = new File(path + LDPI_BASE_PATH);

    }
    // This is working fine but when compiling through command prompt, its through exception
    // private static final Set<String> IMAGE_FORMATS = Set.of("jpg", "jpeg", "png");
    private static final Set<String> IMAGE_FORMATS = new HashSet<>();
    static{
        IMAGE_FORMATS.add("jpg");
        IMAGE_FORMATS.add("jpeg");
        IMAGE_FORMATS.add("png");
    }

    /**
     * Resizes all the images present in input directory to other density type image and write resized image to respective directory
     */
    public void resizeImagesAndWriteToRespectivePackage() {
        initializeTransformationParams();
        final File[] listOfFiles = createDirectoriesAndGetListOfFiles();
        for (final File file : listOfFiles) {
            if (file.isFile()) {
                try {
                    final String name = file.getName();
                    final String ext = name.substring(name.lastIndexOf(".") + 1).toLowerCase();
                    if (!IMAGE_FORMATS.contains(ext))
                        continue;

                    final BufferedImage originalImage = ImageIO.read(file);

                    final float width = originalImage.getWidth();
                    final float height = originalImage.getHeight();
                    final int type = originalImage.getType();


                    System.out.println("name: " + name + "  ext : " + ext + " width : " + width + " Height : " + height);

                    if (selection != 1)
                        new Thread(XXXHDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_XXXHDPI * fin_N / (R_XXXHDPI_D * fin_D)), Math.round(height * R_XXXHDPI * fin_N / (R_XXXHDPI_D * fin_D)));
                                    ImageIO.write(new_image, ext, new File(path + XXXHDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();
                    if (selection != 2)
                        new Thread(XXHDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_XXHDPI * fin_N / (fin_D * R_XXHDPI_D)), Math.round(height * R_XXHDPI * fin_N / (fin_D * R_XXHDPI_D)));
                                    ImageIO.write(new_image, ext, new File(path + XXHDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();
                    if (selection != 3)
                        new Thread(XHDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_XHDPI * fin_N / (R_XHDPI_D * fin_D)), Math.round(height * R_XHDPI * fin_N / (R_XHDPI_D * fin_D)));
                                    ImageIO.write(new_image, ext, new File(path + XHDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();
                    if (selection != 4)
                        new Thread(HDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_HDPI * fin_N / (fin_D * R_HDPI_D)), Math.round(height * R_HDPI * fin_N / (fin_D * R_HDPI_D)));
                                    ImageIO.write(new_image, ext, new File(path + HDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();
                    if (selection != 5)
                        new Thread(MDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_MDPI * fin_N / (fin_D * R_MDPI_D)), Math.round(height * R_MDPI * fin_N / (fin_D * R_MDPI_D)));
                                    ImageIO.write(new_image, ext, new File(path + MDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();
                    if (selection != 6)
                        new Thread(LDPI) {

                            @Override
                            public void run() {
                                try {
                                    BufferedImage new_image = resizeImage(originalImage, type, Math.round(width * R_LDPI * fin_N / (fin_D * R_LDPI_D)), Math.round(height * R_LDPI * fin_N / (fin_D * R_LDPI_D)));
                                    ImageIO.write(new_image, ext, new File(path + LDPI_BASE_PATH + "\\" + name));
                                } catch (Exception E) {
                                    E.printStackTrace();
                                }
                            }
                        }.run();


                } catch (Exception e) {
                }

            }
        }
    }

    /**
     * Initialized base image resolution ratio. this is used to optimize tranformation when input image set is not of type XXXHDPI
     */
    private void initializeTransformationParams() {
        fin_D = 1;
        fin_N = 1;
        switch (selection) {
            case 1:
                break;
            case 2:
                fin_N = R_XXHDPI_D;
                fin_D = R_XXHDPI;
                break;
            case 3:
                fin_N = R_XHDPI_D;
                fin_D = R_XHDPI;
                break;
            case 4:
                fin_N = R_HDPI_D;
                fin_D = R_HDPI;
                break;
            case 5:
                fin_N = R_MDPI_D;
                fin_D = R_MDPI;
                break;
            case 6:
                fin_N = R_LDPI_D;
                fin_D = R_LDPI;
                break;
        }
    }

    /**
     * Creates base directories for each density device and return list of files from current base directory.
     *
     * @return list of files present in base directory
     */
    private File[] createDirectoriesAndGetListOfFiles() {
        if (!folder_LDPI.exists()) {
            folder_LDPI.mkdir();
        }
        if (!folder_MDPI.exists()) {
            folder_MDPI.mkdir();
        }
        if (!folder_HDPI.exists()) {
            folder_HDPI.mkdir();
        }
        if (!folder_XHDPI.exists()) {
            folder_XHDPI.mkdir();
        }
        if (!folder_XXHDPI.exists()) {
            folder_XXHDPI.mkdir();
        }
        if (!folder_XXXHDPI.exists()) {
            folder_XXXHDPI.mkdir();
        }

        folder_LDPI.setWritable(true);
        folder_LDPI.setReadable(true);
        folder_MDPI.setWritable(true);
        folder_MDPI.setReadable(true);
        folder_HDPI.setWritable(true);
        folder_HDPI.setReadable(true);
        folder_XHDPI.setWritable(true);
        folder_XHDPI.setReadable(true);
        folder_XXHDPI.setWritable(true);
        folder_XXHDPI.setReadable(true);
        folder_XXXHDPI.setWritable(true);
        folder_XXXHDPI.setReadable(true);

        switch (selection) {
            case 1:
                return folder_XXXHDPI.listFiles();
            case 2:
                return folder_XXHDPI.listFiles();
            case 3:
                return folder_XHDPI.listFiles();
            case 4:
                return folder_HDPI.listFiles();
            case 5:
                return folder_MDPI.listFiles();
            case 6:
                return folder_LDPI.listFiles();
        }

        System.out.println("No files found to transform.");
        return new File[0];
    }

    /**
     * Resizes image to required size and return {@link BufferedImage} instance of the resized image
     *
     * @param originalImage instance of {@link BufferedImage} containing original image info
     * @param type          type of image
     * @param width         width of new image in pixel
     * @param height        height of new image in pixel
     * @return an instance of {@link BufferedImage} containing detains about resized image
     */
    private static BufferedImage resizeImage(BufferedImage originalImage, int type, int width, int height) {
        System.out.println("Width : " + width + " Height :" + height);

        BufferedImage resizedImage = new BufferedImage(width, height, type);
        Graphics2D g = resizedImage.createGraphics();
        g.drawImage(originalImage, 0, 0, width, height, null);
        g.dispose();

        return resizedImage;
    }

    public static void main(String[] args) {
        final Scanner sc = new Scanner(System.in);
        final String directoryPath;
        if (args.length == 0) {
            System.out.println("Enter path for folder Drawable:");
            directoryPath = sc.nextLine();
        } else {
            directoryPath = args[0];
        }

        System.out.println("Select input image density");
        System.out.println("XXXHDPI_BASE_PATH : 1");
        System.out.println("XXHDPI_BASE_PATH : 2");
        System.out.println("XHDPI_BASE_PATH : 3");
        System.out.println("HDPI_BASE_PATH : 4");
        System.out.println("MDPI_BASE_PATH : 5");
        System.out.println("LDPI_BASE_PATH : 6");
        final int selection = sc.nextInt();
        if (selection < 1 || selection > 6) {
            System.out.println("invalid selection. please try again.");
            return;
        }

        if (isDirectoryPresent(directoryPath, selection)) {
            final AndroidImageTransformer androidImageTransformer = new AndroidImageTransformer(directoryPath, selection);
            androidImageTransformer.resizeImagesAndWriteToRespectivePackage();
        } else {
            System.out.println("Proviced Directory does not exist or input resources are not in standard package. please make sure input paraneters are correct.");
        }

    }

    public static boolean isDirectoryPresent(final String directoryPath, final int selection){
        System.out.println(directoryPath);
        System.out.println(selection);
        String path;
        switch (selection) {
            case 1:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            case 2:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            case 3:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            case 4:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            case 5:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            case 6:
                path = directoryPath + XXXHDPI_BASE_PATH;
                break;
            default: return false;
        }

        return new File(path).exists();
    }
}
