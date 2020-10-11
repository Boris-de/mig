#!/bin/sh

set -e

die() {
  echo ${@} 1>&2
  exit 1
}

create_thumb() {
  local image="${1}"
  local thumbs_dir=`dirname "${image}"`/thumbs
  mkdir -p ${thumbs_dir}
  convert ${image} -resize 128 ${thumbs_dir}/`basename "${image}"`
}

random_image() {
  local mx=320
  local my=256
  head -c "$((3*mx*my))" /dev/urandom | convert -depth 8 -size "${mx}x${my}" RGB:- ${1}
}

random_image_with_thumb() {
  random_image "${1}"
  create_thumb "${1}"
}

test -z "${1}" && die "usage: ${0} directory"
mkdir "${1}" || die "could not create directory"

cd "${1}"

random_image_with_thumb image1.jpg
random_image image2.png
random_image hidden_image.jpg
ffmpeg -hide_banner -loglevel panic -i image2.png video.mp4
touch audio.mp3

cat > mig.cf << EOF
<Bulletin>
Example Bulletin text
</Bulletin>

<Comment "image1.jpg">
random example with a thumbnail
</Comment>

<Comment "image2.png">
random example without a thumbnail
</Comment>

#testcase: short comment should override comment
<Short "image2.png">
short comment
</Short>

#testcase: hidden file
<hidden>
hidden_image.jpg
</hidden>
EOF

for i in `seq 1 9`; do
  FOLDER_NAME=folder${i}
  mkdir "${FOLDER_NAME}"
  cd "${FOLDER_NAME}"

  random_image_with_thumb image_${i}_1.jpg
  random_image image_${i}_2.jpg

  cd ..

  # testcase: use first image's thumbnail for the folder
  echo "UseThumb ${FOLDER_NAME} image_${i}_1.jpg" >> mig.cf
done

# testcase: change sorting in "folder1"
cat > folder1/mig.cf <<EOF
<sort>
image_1_2.jpg
image_1_1.jpg
</sort>
EOF
